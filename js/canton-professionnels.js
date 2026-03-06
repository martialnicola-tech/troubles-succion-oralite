/**
 * Script pour afficher dynamiquement les professionnels par canton
 * À inclure sur les pages cantonales
 */

document.addEventListener('DOMContentLoaded', function() {
    // Déterminer le canton depuis l'URL
    const path = window.location.pathname;
    const canton = path.match(/cantons\/([a-z]+)\.html/)?.[1];
    
    if (!canton) return;
    
    // Charger les professionnels
    loadProfessionnels(canton);
});

async function loadProfessionnels(canton) {
    const container = document.querySelector('.pro-list');
    if (!container) return;
    
    try {
        // Afficher un loader
        container.innerHTML = `
            <div style="text-align: center; padding: 3rem;">
                <div style="display: inline-block; width: 50px; height: 50px; border: 4px solid var(--color-border); border-top-color: var(--color-primary); border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 1rem; color: var(--color-text-light);">Chargement des professionnels...</p>
            </div>
        `;
        
        // Ajouter l'animation du loader si pas déjà présente
        if (!document.getElementById('loader-style')) {
            const style = document.createElement('style');
            style.id = 'loader-style';
            style.textContent = `
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Fetch les professionnels
        const response = await fetch(`../api/get-professionnels-canton.php?canton=${canton}`);
        
        if (!response.ok) {
            throw new Error('Erreur lors du chargement');
        }
        
        const data = await response.json();
        
        // Afficher les résultats
        displayProfessionnels(data, container);
        
    } catch (error) {
        console.error('Erreur:', error);
        container.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: var(--color-text-light);">
                <p>Les professionnels seront affichés prochainement.</p>
                <p style="margin-top: 1rem;">
                    <strong>Vous êtes professionnel de santé ?</strong><br>
                    <a href="../inscription-professionnel.html" class="btn btn-primary" style="margin-top: 1rem; display: inline-block;">
                        Rejoignez notre annuaire
                    </a>
                </p>
            </div>
        `;
    }
}

function displayProfessionnels(data, container) {
    if (data.total === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 2rem;">
                <p style="font-size: 1.1rem; margin-bottom: 1rem;">
                    Aucun professionnel inscrit pour le moment dans ce canton.
                </p>
                <p style="color: var(--color-text-light); margin-bottom: 2rem;">
                    Soyez parmi les premiers à rejoindre notre annuaire !
                </p>
                <a href="../inscription-professionnel.html" class="btn btn-primary">
                    Inscription professionnels
                </a>
            </div>
        `;
        return;
    }
    
    let html = `
        <div style="margin-bottom: 2rem;">
            <h3 style="color: var(--color-primary-dark); text-align: center; margin-bottom: 1rem;">
                ${data.total} professionnel${data.total > 1 ? 's' : ''} dans ce canton
            </h3>
        </div>
    `;
    
    // Afficher par spécialité
    data.specialites.forEach(specialite => {
        html += `
            <div style="margin-bottom: 3rem;">
                <h4 style="color: var(--color-secondary); border-bottom: 2px solid var(--color-primary-light); padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
                    ${specialite.nom} (${specialite.count})
                </h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        `;
        
        specialite.professionnels.forEach(pro => {
            html += createProCard(pro);
        });
        
        html += `
                </div>
            </div>
        `;
    });
    
    // Bouton d'inscription en bas
    html += `
        <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--color-border);">
            <p style="margin-bottom: 1rem;"><strong>Vous êtes professionnel de santé ?</strong></p>
            <a href="../inscription-professionnel.html" class="btn btn-primary">
                Rejoignez notre annuaire - 30 CHF/an
            </a>
        </div>
    `;
    
    container.innerHTML = html;
}

function createProCard(pro) {
    const hasWebsite = pro.site_web && pro.site_web.length > 0;
    
    return `
        <div style="background-color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(74, 66, 56, 0.08); transition: all 0.3s ease;" 
             onmouseover="this.style.boxShadow='0 4px 16px rgba(74, 66, 56, 0.12)'; this.style.transform='translateY(-3px)'" 
             onmouseout="this.style.boxShadow='0 2px 8px rgba(74, 66, 56, 0.08)'; this.style.transform='translateY(0)'">
            
            <h5 style="color: var(--color-primary-dark); margin-bottom: 0.75rem; font-size: 1.2rem;">
                ${pro.prenom} ${pro.nom}
            </h5>
            
            <div style="color: var(--color-text); margin-bottom: 1rem;">
                <p style="margin-bottom: 0.5rem;">
                    <strong>📍</strong> ${pro.adresse}<br>
                    ${pro.code_postal} ${pro.lieu}
                </p>
                
                ${pro.telephone ? `
                    <p style="margin-bottom: 0.5rem;">
                        <strong>📞</strong> ${pro.telephone}
                    </p>
                ` : ''}
                
                <p style="margin-bottom: 0.5rem;">
                    <strong>✉️</strong> <a href="mailto:${pro.email_display}" style="color: var(--color-primary);">Contacter</a>
                </p>
                
                ${hasWebsite ? `
                    <p style="margin-bottom: 0;">
                        <strong>🌐</strong> <a href="${pro.site_web}" target="_blank" rel="noopener" style="color: var(--color-primary);">
                            Site web
                        </a>
                    </p>
                ` : ''}
            </div>
        </div>
    `;
}
