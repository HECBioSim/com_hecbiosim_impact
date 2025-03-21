window.onload = function(){
    function displayPublications(data) {
        const container = document.getElementById("publications");
        container.innerHTML = "";
        data.forEach(pub => {
            const pubElement = document.createElement("div");
            pubElement.classList.add("publication");
            pubElement.innerHTML = `
                <h3>
                    <a href=${pub.doi} target="_blank">${pub.title}</a>
                </h3>
                <div class="pub-details">
                    <span> ${pub.authors.join(", ")} (${pub.year}) <em>${pub.journal}</em> </span>
                </div>
            `;
            container.appendChild(pubElement);
        });
    }

    function filterPublications() {
        const query = document.getElementById("searchBar").value.trim().toLowerCase();
        const typeFilter = document.getElementById("typeFilter").value;
        const dateFilter = document.getElementById("dateFilter").value;
        const projectRefFilter = document.getElementById("projectRefFilter").value;

        const filtered = publicationsData.filter(pub =>
            (pub.title.toLowerCase().includes(query) ||
            pub.authors.some(author => author.toLowerCase().includes(query)) ||
            pub.year.toString().includes(query) ||
            pub.journal.toLowerCase().includes(query)) &&
            (typeFilter === "All" || pub.type === typeFilter) &&
            (dateFilter === "All" || pub.year.toString() === dateFilter) &&
            (projectRefFilter === "All" || pub.projectRef === projectRefFilter)
        );

        displayPublications(filtered);
    }

    document.getElementById("searchBar").addEventListener("input", filterPublications);
    document.getElementById("typeFilter").addEventListener("change", filterPublications);
    document.getElementById("dateFilter").addEventListener("change", filterPublications);
    document.getElementById("projectRefFilter").addEventListener("change", filterPublications);

    let publicationsData;

    const getjson = async () => {
      const response = await fetch("https://hecbiosim.github.io/com_hecbiosim_impact/pubs.json");
      const data = await response.json();
      publicationsData = data;
      return data;
    };

    (async () => {
        await getjson();
        displayPublications(publicationsData);
    })();
}
