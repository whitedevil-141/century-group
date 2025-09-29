const apiUrl = "http://localhost/century-group/api/applications.php";

// ---------------- LOAD APPLICATIONS ----------------
async function loadApps() {
    const res = await fetch(apiUrl + "?action=list", { cache: "no-store" });
    const json = await res.json();
    const tbody = document.querySelector("#appsTable tbody");
    tbody.innerHTML = "";

    if (!json.success) {
        alert(json.message);
        return;
    }

    json.data.forEach(app => {
        const tr = document.createElement("tr");
        tr.innerHTML = `
            <td>${app.name}</td>
            <td>${app.email}</td>
            <td>${app.phone}</td>
            <td>${app.job_title || ""}</td>
            <td><a href="../${app.cv_path}" target="_blank">Download CV</a></td>
            <td>${app.status}</td>
            <td>
                <select id="status-${app.id}">
                    <option value="Pending"  ${app.status === "Pending" ? "selected" : ""}>Pending</option>
                    <option value="Reviewed" ${app.status === "Reviewed" ? "selected" : ""}>Reviewed</option>
                    <option value="Accepted" ${app.status === "Accepted" ? "selected" : ""}>Accepted</option>
                    <option value="Rejected" ${app.status === "Rejected" ? "selected" : ""}>Rejected</option>
                </select>
                <br>
                <button id="btn-${app.id}" onclick="updateStatus(${app.id})">Update</button>
            </td>
        `;

        tbody.appendChild(tr);
    });
}

// ---------------- UPDATE STATUS ----------------
async function updateStatus(id) {
    const status = document.getElementById("status-" + id).value;
    const msgBox = document.getElementById("msg-" + id);
    const message = msgBox ? msgBox.value.trim() : "";
    const btn = document.getElementById("btn-" + id);

    btn.disabled = true;
    btn.textContent = "Updating...";

    const formData = new FormData();
    formData.append("action", "update_status");
    formData.append("id", id);
    formData.append("status", status);
    if (message) formData.append("message", message);

    try {
        const res = await fetch(apiUrl, { method: "POST", body: formData });
        const json = await res.json();
        alert(json.message);
        if (json.success) loadApps();
    } catch (err) {
        alert("Error updating application status");
    } finally {
        btn.disabled = false;
        btn.textContent = "Update";
    }
}

// ---------------- INIT ----------------
loadApps();
