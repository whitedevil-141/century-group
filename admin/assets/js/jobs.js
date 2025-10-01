const apiUrl = "http://localhost/century-group/api/jobs.php";

// ---------------- MODALS ----------------
function openModal(id) { document.getElementById(id).style.display = "flex"; }
function closeModal(id) { document.getElementById(id).style.display = "none"; }

document.querySelectorAll(".modal .close").forEach(btn => {
    btn.onclick = e => closeModal(e.target.closest(".modal").id);
});
window.onclick = e => { if (e.target.classList.contains("modal")) e.target.style.display = "none"; };

document.getElementById("openModalBtn").onclick = () => openModal("jobModal");

// ---------------- LOAD JOBS ----------------
async function loadJobs() {
    const res = await fetch(apiUrl + "?action=list", { cache: "no-store" });
    const json = await res.json();
    const tbody = document.querySelector("#jobTable tbody");
    tbody.innerHTML = "";

    if (!json.success) return alert(json.message);

    json.data.forEach(job => {
        const tr = document.createElement("tr");
        let actions = "";
        if (permissions.jobs.includes("update")) {
            actions += `<button type="button" onclick="editJob(${job.id})">Edit</button>`;
        }
        if (permissions.jobs.includes("delete")) {
            actions += `<button type="button" onclick="deleteJob(${job.id})">Delete</button>`;
        }
        tr.innerHTML = `
            <td>${job.title}</td>
            <td>${job.department_name || ""}</td>
            <td>${job.location || ""}</td>
            <td>${job.job_type}</td>
            <td>${job.created_at}</td>
            <td>${actions}</td>
        `;
        tbody.appendChild(tr);
    });
}

// ---------------- ADD JOB ----------------
document.getElementById("addJobForm").onsubmit = async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch(apiUrl, { method: "POST", body: formData });
    const json = await res.json();
    alert(json.message);
    if (json.success) {
        e.target.reset();
        closeModal("jobModal");
        loadJobs();
    }
};

// ---------------- EDIT JOB ----------------
async function editJob(id) {
    const res = await fetch(apiUrl + "?action=get&id=" + id, { cache: "no-store" });
    const json = await res.json();
    if (!json.success) return alert(json.message);

    const job = json.data;
    document.getElementById("edit-id").value = job.id;
    document.getElementById("edit-title").value = job.title;
    document.getElementById("edit-department").value = job.department_name;
    document.getElementById("edit-location").value = job.location;
    document.getElementById("edit-type").value = job.job_type;
    document.getElementById("edit-description").value = job.description;

    openModal("editModal");
}

document.getElementById("editJobForm").onsubmit = async e => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const res = await fetch(apiUrl, { method: "POST", body: formData });
    const json = await res.json();
    alert(json.message);
    if (json.success) {
        closeModal("editModal");
        loadJobs();
    }
};

// ---------------- DELETE JOB ----------------
async function deleteJob(id) {
    if (!confirm("Delete this job?")) return;
    const res = await fetch(apiUrl + `?action=delete&id=${id}`);
    const json = await res.json();
    alert(json.message);
    if (json.success) loadJobs();
}

// ---------------- INIT ----------------
loadJobs();
