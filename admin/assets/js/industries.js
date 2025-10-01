const apiUrl = "http://localhost/century-group/api/industries.php";

// ---------------- MODALS ----------------
function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

document.querySelectorAll(".modal .close").forEach(btn => {
    btn.onclick = e => closeModal(e.target.closest(".modal").id);
});
window.onclick = e => { if (e.target.classList.contains("modal")) e.target.style.display = "none"; };

document.getElementById("openModalBtn").onclick = () => openModal("industryModal");

// ---------------- LOAD INDUSTRIES ----------------
async function loadIndustries() {
    const res = await fetch(apiUrl + "?action=list", { cache: "no-store" });
    const json = await res.json();
    const tbody = document.querySelector("#industryTable tbody");
    tbody.innerHTML = "";

    if (!json.success) return alert(json.message);

    json.data.forEach(ind => {
        const tr = document.createElement("tr");

        let actions = "";

        if (permissions.industries.includes("update")) {
            actions += `<button type="button" onclick="editIndustry(${ind.id})">Edit</button>`;
            actions += `<button type="button" onclick="toggleIndustry(${ind.id})">
                            ${ind.status == 1 ? "Disable" : "Enable"}
                        </button>`;
        }

        if (permissions.industries.includes("delete")) {
            actions += `<button type="button" onclick="deleteIndustry(${ind.id})">Delete</button>`;
        }

        tr.innerHTML = `
            <td>${ind.name}</td>
            <td>${ind.slug}</td>
            <td>${ind.icon ? `<img src="../public/${ind.icon}" class="thumb" style="background:#ccc">` : ""}</td>
            <td>${ind.images.map(i => `<img src="../public/${i}" class="thumb">`).join("")}</td>
            <td>${ind.description || ""}</td>
            <td>${ind.status == 1 ? "✅ Active" : "❌ Inactive"}</td>
            <td>${ind.created_at}</td>
            <td>${actions || "—"}</td>
        `;

        tbody.appendChild(tr);
    });
}

// ---------------- ADD ----------------
document.getElementById("addIndustryForm").onsubmit = async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch(apiUrl, { method: "POST", body: formData });
    const json = await res.json();
    alert(json.message);
    if (json.success) {
        loadIndustries();
        e.target.reset();
        closeModal("industryModal");

    }
};

// ---------------- EDIT ----------------
async function editIndustry(id) {
    const res = await fetch(apiUrl + "?action=get&id=" + id, { cache: "no-store" });
    const json = await res.json();
    if (!json.success) return alert(json.message);

    const ind = json.data;
    document.getElementById("edit-id").value = ind.id;
    document.getElementById("edit-name").value = ind.name;
    document.getElementById("edit-description").value = ind.description;

    const iconPrev = document.getElementById("edit-icon-preview");
    iconPrev.innerHTML = ind.icon ? `<img src="../public/${ind.icon}" class="thumb">` : "";

    renderEditImages(ind);

    openModal("editModal");
}

function renderEditImages(ind) {
    const imagesPrev = document.getElementById("edit-images-preview");
    imagesPrev.innerHTML = ind.images.map((img, idx) => `
        <div class="image-wrapper" id="img-${idx}">
            <img src="../public/${img}">
            <div class="actions">
                <button type="button" class="delete" onclick="deleteImage(${ind.id}, ${idx})">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    `).join("");
}

document.getElementById("editIndustryForm").onsubmit = async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch(apiUrl, { method: "POST", body: formData });
    const json = await res.json();
    alert(json.message);
    if (json.success) {
        e.target.reset();
        loadIndustries();
        closeModal("editModal");
    }
};

// ---------------- DELETE ----------------
async function deleteIndustry(id) {
    if (!confirm("Delete this industry?")) return;
    const res = await fetch(apiUrl + `?action=delete&id=${id}`);
    const json = await res.json();
    alert(json.message);
    if (json.success) loadIndustries();
}

// ---------------- TOGGLE ----------------
async function toggleIndustry(id) {
    const res = await fetch(apiUrl + `?action=toggle&id=${id}`);
    const json = await res.json();
    alert(json.message);
    if (json.success) loadIndustries();
}

// ---------------- DELETE IMAGE ----------------
async function deleteImage(id, key) {
    if (!confirm("Delete this image?")) return;
    const res = await fetch(apiUrl + `?action=delete_image&id=${id}&imgKey=${key}`);
    const json = await res.json();
    alert(json.message);

    if (json.success) {
        // remove the DOM element immediately without reloading everything
        const imgWrapper = document.getElementById("img-" + key);
        if (imgWrapper) imgWrapper.remove();
    }
}

// ---------------- INIT ----------------
loadIndustries();
