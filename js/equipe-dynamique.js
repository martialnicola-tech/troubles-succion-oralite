(function() {
  const SPECIALITE_LABELS = {
    'osteopathe': 'Ostéopathe',
    'sage-femme': 'Sage-femme',
    'ibclc': 'IBCLC',
    'consultante-lactation': 'Consultante en lactation',
    'logopediste': 'Logopédiste',
    'orl': 'ORL / Médecin-dentiste',
    'nutritionniste': 'Nutritionniste',
    'dieteticienne': 'Diététicienne',
    'physiotherapeute': 'Physiothérapeute'
  };

  function getLabel(specialite) {
    if (!specialite) return '';
    const sp = specialite.toLowerCase().trim();
    for (const key of Object.keys(SPECIALITE_LABELS)) {
      if (sp.includes(key)) return SPECIALITE_LABELS[key];
    }
    return specialite;
  }

  function renderCard(pro) {
    const name = (pro.prenom || '') + ' ' + (pro.nom || '');
    const label = getLabel(pro.specialite);
    let infos = '';

    // Description / sous-titre professionnel
    if (pro.description) infos += `<div style="font-style:italic; color:#666; font-size:0.83rem; margin-bottom:0.3rem;">${pro.description}</div>`;

    // Cabinet principal + adresse
    if (pro.cabinet) infos += `<div class="annuaire-cabinet">🏥 ${pro.cabinet}</div>`;
    if (pro.adresse) {
      pro.adresse.split(' / ').forEach(part => {
        if (part.trim()) infos += `<div>${part.trim()}</div>`;
      });
    }
    if (pro.npa && pro.lieu) infos += `<div>${pro.npa} ${pro.lieu}</div>`;
    else if (pro.lieu) infos += `<div>${pro.lieu}</div>`;

    // 2ème cabinet / adresse
    if (pro.adresse2 || pro.lieu2) {
      infos += `<div style="margin-top:0.35rem; padding-top:0.35rem; border-top:1px dashed #eee;">`;
      if (pro.cabinet2) infos += `<div class="annuaire-cabinet">🏥 ${pro.cabinet2}</div>`;
      if (pro.adresse2) {
        pro.adresse2.split(' / ').forEach(part => {
          if (part.trim()) infos += `<div>${part.trim()}</div>`;
        });
      }
      if (pro.cp2 && pro.lieu2) infos += `<div>${pro.cp2} ${pro.lieu2}</div>`;
      else if (pro.lieu2) infos += `<div>${pro.lieu2}</div>`;
      infos += `</div>`;
    }

    // Téléphones
    if (pro.telephone) infos += `<div class="annuaire-phone">📞 ${pro.telephone}</div>`;
    if (pro.telephone2) infos += `<div class="annuaire-phone">📞 ${pro.telephone2}</div>`;

    if (pro.email) infos += `<div><a href="mailto:${pro.email}">✉️ ${pro.email}</a></div>`;
    if (pro.site_web) infos += `<div><a href="${pro.site_web}" target="_blank" rel="nofollow">🌐 ${pro.site_web.replace(/https?:\/\/(www\.)?/, '')}</a></div>`;

    return `<div class="annuaire-card">
      <div class="annuaire-name">${name}</div>
      <span class="annuaire-specialty">${label}</span>
      <div class="annuaire-info">${infos}</div>
    </div>`;
  }

  function loadEquipe(container, specialite) {
    container.innerHTML = '<p style="color:#999;padding:1rem;">Chargement…</p>';
    fetch('/api/search-professionnels.php?specialite=' + encodeURIComponent(specialite))
      .then(r => r.json())
      .then(data => {
        if (!data.success || !data.professionnels.length) {
          container.innerHTML = '<p style="color:#999;">Aucun professionnel trouvé.</p>';
          return;
        }
        // Priorité : gratuit en premier, puis active, puis pending
        const order = { gratuit: 0, active: 1, pending: 2 };
        const sorted = data.professionnels.sort((a, b) =>
          (order[a.status] ?? 1) - (order[b.status] ?? 1)
        );
        container.innerHTML = sorted.map(renderCard).join('');
      })
      .catch(() => {
        container.innerHTML = '<p style="color:#c00;">Erreur de chargement.</p>';
      });
  }

  document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('equipe-pros-grid');
    if (!grid) return;
    const specialite = grid.getAttribute('data-specialite');
    if (!specialite) return;
    loadEquipe(grid, specialite);
  });
})();
