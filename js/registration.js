// JavaScript pour le formulaire d'inscription professionnel

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Validation en temps réel
    const fields = {
        'specialite': { required: true },
        'prenom': { required: true, minLength: 2 },
        'nom': { required: true, minLength: 2 },
        'adresse': { required: true, minLength: 5 },
        'cp': { required: true, pattern: /^[0-9]{4}$/ },
        'lieu': { required: true, minLength: 2 },
        'email': { required: true, pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/ },
        'telephone': { required: false },
        'siteWeb': { required: false, pattern: /^https?:\/\/.+/ },
        'acceptConditions': { required: true, type: 'checkbox' },
        'acceptPaiement': { required: true, type: 'checkbox' }
    };
    
    // Ajouter la validation sur blur pour chaque champ
    Object.keys(fields).forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field) {
            field.addEventListener('blur', () => validateField(fieldName));
            field.addEventListener('input', () => {
                if (field.classList.contains('error')) {
                    validateField(fieldName);
                }
            });
        }
    });
    
    // Validation d'un champ
    function validateField(fieldName) {
        const field = document.getElementById(fieldName);
        const error = document.getElementById(`error-${fieldName}`);
        const rules = fields[fieldName];
        
        if (!field || !rules) return true;
        
        let isValid = true;
        let errorMessage = '';
        
        // Vérifier si requis
        if (rules.required) {
            if (rules.type === 'checkbox') {
                if (!field.checked) {
                    isValid = false;
                    errorMessage = error.textContent;
                }
            } else {
                if (!field.value.trim()) {
                    isValid = false;
                    errorMessage = error.textContent;
                }
            }
        }
        
        // Vérifier la longueur minimale
        if (isValid && rules.minLength && field.value.trim().length < rules.minLength) {
            isValid = false;
            errorMessage = `Ce champ doit contenir au moins ${rules.minLength} caractères`;
        }
        
        // Vérifier le pattern
        if (isValid && rules.pattern && field.value.trim()) {
            if (!rules.pattern.test(field.value.trim())) {
                isValid = false;
                errorMessage = error.textContent;
            }
        }
        
        // Afficher/cacher l'erreur
        if (isValid) {
            field.classList.remove('error');
            if (error) error.classList.remove('show');
        } else {
            field.classList.add('error');
            if (error) {
                error.textContent = errorMessage;
                error.classList.add('show');
            }
        }
        
        return isValid;
    }
    
    // Validation complète du formulaire
    function validateForm() {
        let isValid = true;
        
        Object.keys(fields).forEach(fieldName => {
            if (!validateField(fieldName)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
    
    // Soumission du formulaire
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Valider le formulaire
        if (!validateForm()) {
            // Scroll vers la première erreur
            const firstError = document.querySelector('.form-input.error, .checkbox-input:not(:checked)');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }
        
        // Désactiver le bouton pendant le traitement
        submitBtn.disabled = true;
        submitBtn.textContent = 'Traitement en cours...';
        
        // Récupérer les données du formulaire
        const formData = {
            specialite: document.getElementById('specialite').value,
            prenom: document.getElementById('prenom').value,
            nom: document.getElementById('nom').value,
            adresse: document.getElementById('adresse').value,
            cp: document.getElementById('cp').value,
            lieu: document.getElementById('lieu').value,
            email: document.getElementById('email').value,
            telephone: document.getElementById('telephone').value,
            siteWeb: document.getElementById('siteWeb').value,
            acceptConditions: document.getElementById('acceptConditions').checked,
            acceptPaiement: document.getElementById('acceptPaiement').checked
        };
        
        try {
            // Envoyer les données au serveur
            const response = await fetch('api/create-registration.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Mettre à jour les étapes
                updateStep(2);
                
                // Si un site web est fourni, montrer l'étape de vérification
                if (formData.siteWeb) {
                    showVerificationStep(result.registrationId, formData.siteWeb);
                } else {
                    // Sinon, passer directement au paiement
                    redirectToPayment(result.checkoutUrl);
                }
            } else {
                alert('Erreur lors de l\'inscription : ' + result.message);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Continuer vers le paiement';
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Continuer vers le paiement';
        }
    });
    
    // Mettre à jour l'indicateur d'étape
    function updateStep(stepNumber) {
        for (let i = 1; i <= 3; i++) {
            const step = document.getElementById(`step-${i}`);
            if (i < stepNumber) {
                step.classList.add('completed');
                step.classList.remove('active');
            } else if (i === stepNumber) {
                step.classList.add('active');
                step.classList.remove('completed');
            } else {
                step.classList.remove('active', 'completed');
            }
        }
    }
    
    // Afficher l'étape de vérification du lien
    function showVerificationStep(registrationId, siteWeb) {
        const formContainer = document.querySelector('.form-container');
        
        formContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem 0;">
                <h2 style="color: var(--color-primary-dark); margin-bottom: 1.5rem;">
                    Vérification du lien retour
                </h2>
                
                <div class="verification-notice" style="text-align: left;">
                    <h4>📋 Prochaine étape : Ajoutez notre lien sur votre site</h4>
                    <p>Avant de procéder au paiement, veuillez ajouter un lien vers <strong>www.troubles-succion-oralite.ch</strong> sur votre site web :</p>
                    <p style="margin-top: 1rem;"><strong>${siteWeb}</strong></p>
                    
                    <div style="background-color: white; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                        <p style="margin-bottom: 0.5rem; font-weight: 600;">Code HTML à copier :</p>
                        <code style="display: block; padding: 1rem; background-color: #f5f5f5; border-radius: 4px; font-family: monospace; font-size: 0.9rem; overflow-x: auto;">
&lt;a href="https://www.troubles-succion-oralite.ch" target="_blank"&gt;Troubles de la Succion&lt;/a&gt;
                        </code>
                    </div>
                    
                    <p style="margin-top: 1rem;">
                        Une fois le lien ajouté, cliquez sur "Vérifier le lien" ci-dessous. Notre système vérifiera automatiquement la présence du lien.
                    </p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <button onclick="checkBacklink('${registrationId}', '${siteWeb}')" class="btn btn-primary" style="margin-right: 1rem;">
                        Vérifier le lien
                    </button>
                    <button onclick="skipBacklink('${registrationId}')" class="btn" style="background-color: var(--color-secondary);">
                        Passer cette étape (le lien ne sera pas activé)
                    </button>
                </div>
                
                <p style="margin-top: 1.5rem; color: var(--color-text-light); font-size: 0.9rem;">
                    <em>Note : Si vous choisissez de passer cette étape, votre site web ne sera pas affiché dans votre profil tant que le lien retour n'aura pas été vérifié.</em>
                </p>
            </div>
        `;
    }
    
    // Fonction globale pour vérifier le backlink
    window.checkBacklink = async function(registrationId, siteWeb) {
        const btn = event.target;
        btn.disabled = true;
        btn.textContent = 'Vérification en cours...';
        
        try {
            const response = await fetch('api/verify-backlink.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ registrationId, siteWeb })
            });
            
            const result = await response.json();
            
            if (result.success) {
                updateStep(3);
                alert('✓ Lien vérifié avec succès ! Vous allez être redirigé vers le paiement.');
                redirectToPayment(result.checkoutUrl);
            } else {
                alert('❌ Le lien n\'a pas été trouvé sur votre site. Veuillez vérifier que vous avez bien ajouté le lien et réessayer.');
                btn.disabled = false;
                btn.textContent = 'Vérifier le lien';
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la vérification. Veuillez réessayer.');
            btn.disabled = false;
            btn.textContent = 'Vérifier le lien';
        }
    };
    
    // Fonction globale pour passer l'étape de vérification
    window.skipBacklink = async function(registrationId) {
        if (confirm('Êtes-vous sûr de vouloir passer cette étape ? Votre site web ne sera pas affiché dans votre profil.')) {
            try {
                const response = await fetch('api/skip-backlink.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ registrationId })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    updateStep(3);
                    redirectToPayment(result.checkoutUrl);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Une erreur est survenue. Veuillez réessayer.');
            }
        }
    };
    
    // Redirection vers le paiement Stripe
    function redirectToPayment(checkoutUrl) {
        window.location.href = checkoutUrl;
    }
});
