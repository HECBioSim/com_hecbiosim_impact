""" This script forms part of an automated workflow 
to extract ResearchFish data on publications
from an Excel spreadsheet.
It relies on data from a YAML input file, and data on 
publications from a JSON input file, calculates 
a range of statistics and creates a second
JSON file containing these statistics"""

import json
from collections import Counter
import os
import yaml


def load_config(yaml_file="config.yaml"):
    """Load configuration from config.yaml"""
    with open(yaml_file, "r", encoding="utf8") as file:
        return yaml.safe_load(file)


with open("../pubs.json", encoding="utf8") as f:
    data = json.load(f)

config = load_config()

project_codes = list(config.get("projects", {}).keys())

top_journals = {
    "Nature",
    "Science",
    "PNAS",
    "Cell",
    "JACS",
    "Journal of the American Chemical Society",
}

year_counts = Counter()
journal_counts = Counter()
project_year_counts = {code: Counter() for code in project_codes}
papers_per_grant_count = {code: 0 for code in project_codes}
month_counts = Counter()
unique_authors = set()

for entry in data:
    project_refs = entry.get("projectRef", "Unknown")
    year = entry.get("year", "Unknown")
    month = entry.get("month", "Unknown")
    journal = entry.get("journal", "Unknown")
    authors = entry.get("authors", [])

    # Count publications per year
    if isinstance(year, int):
        year_counts[year] += 1
        tot_top_journals = sum(journal_counts.values())

    # Count publications per grant code
    project_list = [proj.strip() for proj in project_refs.split(",")]
    for project in project_list:
        if project in project_year_counts:
            project_year_counts[project][year] += 1
            papers_per_grant_count[project] += 1

    # Count publications per month (all-time total)
    if isinstance(month, str):
        month_counts[month] += 1

    # Sort months
    MONTH_ORDER = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
    ]

    ordered_months = [month for month in MONTH_ORDER if month in month_counts]
    ordered_counts = [month_counts[month] for month in ordered_months]

    # Count number of top journals
    if any(journal.lower().startswith(j.lower()) for j in top_journals):
        journal_counts[journal] += 1

    # Extract and count unique authors
    for author_list in authors:
        author_names = [name.strip() for name in author_list.split(",")]
        unique_authors.update(author_names)

# Convert project_year_counts to lists for JSON
project_years_json = {
    grant: {"x": list(year_count.keys()), "y": list(year_count.values())}
    for grant, year_count in project_year_counts.items()
}

json_data = {
    "totalPublications": len(data),
    "papersPerGrantCount": papers_per_grant_count,
    "totalTopPapers": tot_top_journals,
    "uniqueAuthors": len(unique_authors),
    "papersPerGrant": project_years_json,
    "totalPublicationsPerYear": {
        "x": list(year_counts.keys()),
        "y": list(year_counts.values()),
    },
    "totalPublicationsPerMonth": {"x": list(ordered_months), "y": list(ordered_counts)},
    "topJournals": {
        "x": list(journal_counts.keys()),
        "y": list(journal_counts.values()),
    },
}

output_file = os.path.join(os.pardir, config["stats_json_output_file"])
with open(output_file, "w", encoding="utf8") as json_file:
    json.dump(json_data, json_file, indent=4)
