document.addEventListener("DOMContentLoaded", function () {

  // 1) Toggle bloc groupe
  const selectType = document.getElementById("fi_type");
  const blocGroupe = document.getElementById("fi-bloc-groupe");

  if (selectType && blocGroupe) {
    function toggleBloc() {
      blocGroupe.hidden = (selectType.value !== "groupe");
    }
    selectType.addEventListener("change", toggleBloc);
    toggleBloc();
  }

  // --- Calcul total participants ---
  const adultInput = document.getElementById("fi_adult_nb");
  const childInput = document.getElementById("fi_child_nb");
  const totalSpan = document.getElementById("fi_total");
console.log("adultInput", adultInput);
console.log("childInput", childInput);
console.log("totalSpan", totalSpan);

  function updateTotal() {
    console.log("updateTotal()", adultInput?.value, childInput?.value);
    if (!adultInput || !childInput || !totalSpan) return;

    const adults = parseInt(adultInput.value || 0, 10);
    const children = parseInt(childInput.value || 0, 10);

    const total = Math.max(0, adults) + Math.max(0, children);
    totalSpan.textContent = total;
  }

  if (adultInput && childInput) {
    adultInput.addEventListener("input", updateTotal);
    childInput.addEventListener("input", updateTotal);
    updateTotal(); // initialise au chargement
  }
});