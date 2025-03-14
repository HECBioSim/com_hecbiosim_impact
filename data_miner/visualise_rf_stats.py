"""This script extracts data from a specifically named JSON
file and processes it to get a range of graphics"""

import json
import yaml
from collections import Counter
import plotly.express as px
from plotly.offline import plot

def load_config(yaml_file="config.yaml"):
    """Load a file named config.yaml and return it"""
    with open(yaml_file, "r", encoding="utf8") as file:
        return yaml.safe_load(file)

# Load JSON data
with open("hec_bio_sim_pubs.json", encoding="utf8") as f:
    data = json.load(f)

# Load configuration
config = load_config()

# Define project codes to check

project_codes = list(config.get("projects", {}).keys())

# Initialize counters
project_counts = {code: 0 for code in project_codes}
year_counts = Counter()
journal_counts = Counter()
for entry in data:
    project_refs = entry.get("projectRef", "Unknown")
    year = entry.get("year", "Unknown")
    journal = entry.get("journal", "Unknown")

    # Split project references if multiple refs present
    project_list = [proj.strip() for proj in project_refs.split(",")]

    # Count each project separately
    for project in project_list:
        if project in project_counts:
            project_counts[project] += 1

    # Count publications by year
    if isinstance(year, int):  # Ensure year is valid
        year_counts[year] += 1

    #Count top journal publications total number
    if journal in ["Nature"]:
        journal_counts[journal] += 1

#Get years by date and number of years 
all_years = list(range(min(year_counts.keys()), max(year_counts.keys()) + 1))
year_values = [year_counts.get(year, 0) for year in all_years]

print(journal_counts)
# Generate Bar Chart for Publications by Project Reference
fig_bar = px.bar(
    x=list(project_counts.keys()),
    y=list(project_counts.values()),
    labels={"x": "Project Reference", "y": "Publication Count"},
    title="Publications per Project Reference",
)

# Generate Pie Chart for Publications by Project Reference
fig_pie = px.pie(
    names=list(project_counts.keys()),
    values=list(project_counts.values()),
    title="Publications Distribution by Project",
)

# Generate Bar Chart for Publications per Year (ensuring all years appear)
fig_years = px.bar(
    x=all_years,
    y=year_values,
    labels={"x": "Year", "y": "Publication Count"},
    title="Publications per Year",
)

# Save charts as HTML
plot_html = """
<html>
<head>
    <title>Publication Data Visualization</title>
</head>
<body>
    <h1>Publication Data Charts</h1>
    {bar_chart}
    {pie_chart}
    {year_chart}
</body>
</html>
""".format(
    bar_chart=plot(fig_bar, output_type="div", include_plotlyjs="cdn"),
    pie_chart=plot(fig_pie, output_type="div", include_plotlyjs=False),
    year_chart=plot(fig_years, output_type="div", include_plotlyjs=False),
)

# Save the HTML output to a file
with open("publication_charts.php", "w", encoding="utf8") as f:
    f.write(plot_html)

print("Charts saved to 'publication_charts.html'")
