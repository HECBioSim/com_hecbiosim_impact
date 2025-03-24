""" This script forms part of an automated workflow 
to extract ResearchFish data on publications
from an Excel spreadsheet.
It relies on data from a YAML input file, and outputs
the publication data as a json file
which can be injected into an html file within a plugin,
for use on a CMS """

import json
import calendar
import yaml
import os
import pandas as pd


def load_config(yaml_file="config.yaml"):
    """Load a file named config.yaml and return it"""
    with open(yaml_file, "r", encoding="utf8") as file:
        return yaml.safe_load(file)


def format_authors_list(authors_str):
    """Split the author list on "," and process each name into the "lastname, initial, initial"
    format. Check for edge cases where names have prefixes, and adjust for this.
    Returns string of formatted authors"""
    #Remove whitespace
    authors_list = [
        name.strip() for name in authors_str.split(",")
    ]  
    formatted_authors = []

    for author in authors_list:
        parts = author.split()
        if not parts:  # Skip empty entries
            continue

        last_name = parts[0]  # Default: First word is last name
        initials = ""

        if len(parts) > 1:
            if parts[0].lower() in {"von", "van"} and parts[1].lower() not in {
                "der",
                "den",
            }:
                last_name = f"{parts[0]} {parts[1]}"
                initials = ".".join(
                    f"{part[0]}." for part in parts[2:]
                )  # Process initials
            elif parts[1].lower() in {"der", "den"} and len(parts) > 2:
                last_name = f"{parts[0]} {parts[1]} {parts[2]}"
                initials = ".".join(f"{part[0]}." for part in parts[3:])
            else:
                initials = ".".join(f"{part[0]}." for part in parts[1:])

        formatted_authors.append(f"{last_name}, {initials}" if initials else last_name)

    # Combine formatted authors, join with a comma except for final name, which is joined with "and"
    if len(formatted_authors) > 1:
        return ", ".join(formatted_authors[:-1]) + " and " + formatted_authors[-1]
    return formatted_authors

def remove_full_stop(pub_title):
    """Remove full stops from the end of titles. Returns formatted title"""
    if isinstance(pub_title, str) and pub_title[-1] in {"."}:
        new_title = pub_title.rstrip(".")
    else:
        new_title = pub_title

    return new_title

def format_journal(journal_title):
    """Convert titles to standard format (each letter capitalised)
    Returns formatted journal title"""
    if isinstance(journal_title, str):
        new_journal_title = journal_title.title()
    else:
        new_journal_title = ""

    return new_journal_title

def format_doi(doi):
    """Convert DOI code to full DOI"""
    if pd.isna(doi):
        doi = "#"
    else:
        doi = f"https://doi.org/{doi}"
    return doi 

# Load configuration
config = load_config()

# Required columns
required_fields = [
    "Publication*",
    "Journal*",
    "Author*",
    "Other Authors",
    "Year*",
    "Month",
    "OpenAire Fulltext URL",
    "Type*",
    "Chapter Title*",
    "Chapter Author",
    "Other Chapter Authors",
    "DOI"
]

# List to store all RF data
all_dataframes = []

for project_code, file_path in config["projects"].items():
    print(f"Processing {file_path} for project {project_code}")

    try:
        df = pd.read_excel(file_path)

        # Check for missing fields
        missing_fields = [field for field in required_fields if field not in df.columns]
        if missing_fields:
            print(f"Skipping {file_path}, missing fields: {', '.join(missing_fields)}")
            continue

        # Add matching project code to  dataframe
        df["ProjectRef"] = project_code

        #Remove any full stops to publication titles, to assist dupicate detection
        df["Publication*"] = df["Publication*"].apply(remove_full_stop)
      
      # Remove duplicates on publication title, checking against a tempory lower-case column
        df["temp_lower"] = df["Publication*"].str.lower()
        df = df.drop_duplicates(subset=["temp_lower"]).drop(columns=["temp_lower"])

        # Store the DataFrame in preparation for merging
        all_dataframes.append(df)

    except Exception as e:
        print(f"Error processing {file_path}: {e}")

# Join dataframes vertically
if all_dataframes:
    combined_df = pd.concat(all_dataframes, ignore_index=True)
     
    # Fill NaN fields in Year and Month with 0
    combined_df["Year*"] = (
        combined_df["Year*"].fillna(0).astype(int)
    )
     combined_df["Month"] = (
        combined_df["Month"].fillna(0).astype(int)
    )

    #Remove any full stops to publication titles, to assist dupicate detection
    combined_df["Publication*"] = combined_df["Publication*"].apply(remove_full_stop)

    #Remove publications before 2013 (first year of first grant)
    combined_df = combined_df[combined_df["Year*"] >= 2013] 

    #Sort by year, newest first
    combined_df = combined_df.sort_values(
        by=["Year*", "Month"], ascending=False
    )  
   
    # Remove duplicates on publication title, checking against a tempory lower-case column
    combined_df["temp_lower"] = combined_df["Publication*"].str.lower()
    #Group by publication title (ignoring case) and aggregate project codes
    combined_df["ProjectRef"] = combined_df.groupby("temp_lower")["ProjectRef"].transform(lambda x: ", ".join(sorted(set(x))))
    combined_df = combined_df.drop_duplicates(subset=["temp_lower"]).drop(columns=["temp_lower"])

    # Build JSON entries
    json_entries = []
    for _, row in combined_df.iterrows():
        entry = {
            "title": row["Publication*"],
            "journal": format_journal(row["Journal*"]),
            "year": row["Year*"],
            "month": calendar.month_name[int(row["Month"])],
            "projectRef": row["ProjectRef"],
            "type": row.get("Type*", ""),
            "doi": format_doi(row["DOI"]),
        }
        print(row["Month"])

        author_types = ["Author*", "Other Authors","Chapter Author","Other Chapter Authors"]
        authors = [] 
        for AUTHOR_TYPE in author_types:
            if AUTHOR_TYPE in row  and pd.notna(row[AUTHOR_TYPE]):
                authors.append(row[AUTHOR_TYPE])
        if authors:  
            fully_formatted_authors = format_authors_list(", ".join(authors))
            entry["authors"] = (
                fully_formatted_authors
                if isinstance(fully_formatted_authors, list)
                else [fully_formatted_authors]
            )
            

        json_entries.append(entry)

    # Save JSON output
    json_content = json.dumps(json_entries, indent=4)
    with open(os.path.join(os.pardir,config["pubs_json_output_file"]), "w", encoding="utf8") as json_file:
        json_file.write(json_content)
    print(f"JSON saved to {config['pubs_json_output_file']}")

   
