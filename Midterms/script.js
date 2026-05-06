const API_URL = "https://dragonball-api.com/api/characters";

let allCharacters = [];

/* =========================
   LOAD CHARACTERS (LIMIT)
========================= */
async function loadCharacters(limit = 100) {
  try {
    const res = await fetch(`${API_URL}?limit=${limit}`);
    const data = await res.json();

    allCharacters = data.items || data || [];

    displayCharacters(allCharacters);

  } catch (error) {
    console.error("Error loading characters:", error);
  }
}

/* =========================
   VIEW ALL CHARACTERS
========================= */
async function loadAllCharacters() {
  try {
    const res = await fetch(API_URL);
    const data = await res.json();

    allCharacters = data.items || data || [];

    displayCharacters(allCharacters);

  } catch (err) {
    console.error("Error loading all:", err);
  }
}

/* =========================
   DISPLAY CHARACTERS (FIXED)
========================= */
function displayCharacters(characters) {
  const container = document.getElementById("characters");
  const countText = document.getElementById("countText");

  if (!container) return;

  container.innerHTML = "";

  // 🔥 ONLY 3 CHARACTERS
  const allowed = ["goku", "vegeta", "piccolo"];

  const filtered = characters.filter(char => {
    const name = (char.name || "").toLowerCase();
    return allowed.includes(name);
  });

  if (countText) {
    countText.innerText = `${filtered.length} characters found`;
  }

  let html = "";

  filtered.forEach(char => {

    const name = (char.name || "").toLowerCase();

    // 🔥 FIX RACE
    let race = "Unknown";

    if (name === "goku") race = "Saiyan";
    if (name === "vegeta") race = "Saiyan";
    if (name === "piccolo") race = "Namekian";

    html += `
      <div class="col-md-4 mb-4">
        <div class="character-card">

          <!-- HEART -->
          <i class="fa-regular fa-heart heart"
             onclick="toggleHeart(this)"></i>

          <!-- IMAGE -->
          <img src="${char.image || ''}"
               class="character-img"
               alt="${char.name || ''}">

          <!-- INFO -->
          <div class="character-info">
            <h5>${char.name || "Unknown"}</h5>
            <p>${race}</p>
          </div>

        </div>
      </div>
    `;
  });

  container.innerHTML = html || "<p>No characters found</p>";
}

/* =========================
   SEARCH (REAL TIME)
========================= */
function searchCharacter() {
  const input = document.getElementById("searchInput");
  const value = (input?.value || "").toLowerCase();

  const filtered = allCharacters.filter(char =>
    (char.name || "").toLowerCase().includes(value)
  );

  displayCharacters(filtered);
}

/* =========================
   MODAL DETAILS (OPTIONAL READY)
========================= */
function showDetails(char) {
  const modalContent = document.getElementById("modalContent");

  modalContent.innerHTML = `
    <div class="text-center">

      <img src="${char.image}"
           class="img-fluid mb-3"
           style="height:200px">

      <h3>${char.name}</h3>
      <p>${char.race || "Unknown"}</p>

      <p><strong>Ki:</strong> ${char.ki || "Unknown"}</p>
      <p><strong>Affiliation:</strong> ${char.affiliation || "Unknown"}</p>

    </div>
  `;

  const modal = new bootstrap.Modal(
    document.getElementById("characterModal")
  );

  modal.show();
}

/* =========================
   FORMAT NAME
========================= */
function formatName(name) {
  if (!name) return "Unknown";

  return name
    .toLowerCase()
    .split(" ")
    .map(w => w.charAt(0).toUpperCase() + w.slice(1))
    .join(" ");
}

/* =========================
   HEART TOGGLE
========================= */
function toggleHeart(el) {
  el.classList.toggle("fa-regular");
  el.classList.toggle("fa-solid");
  el.classList.toggle("active");
}

function scrollToCharacters() {
  document.getElementById("charactersSection")
    .scrollIntoView({ behavior: "smooth" });
}

/* =========================
   INIT
========================= */
loadCharacters();