<?php 
$pageTitle = "Planificateur de Journée - Studio Toon";
ob_start(); 
?>

<div class="page-entete" style="max-width: 900px; margin: 0 auto 2rem auto; background: var(--violet); color: #fff; border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
    <h1 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; margin: 0; font-size: 2rem;">🎯 Mon Planificateur de Visite</h1>
    <p class="page-entete__intro" style="font-weight: 700; margin-top: 0.5rem;">Optimisez votre journée selon votre taille et découvrez l'ordre de visite idéal pour éviter les files d'attente !</p>
</div>

<div style="max-width: 900px; margin: 0 auto; display: flex; flex-direction: column; gap: 2rem;">

    <!-- Formulaire de configuration du profil visiteur -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            📏 Vos critères de visite
        </h2>

        <form method="GET" action="index.php" style="display: flex; gap: 1.5rem; align-items: flex-end; flex-wrap: wrap; margin-top: 1.5rem;">
            <input type="hidden" name="action" value="planificateur">
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">Votre taille (en mètres) :</label>
                <input type="number" step="0.01" name="taille_visiteur" value="<?= htmlspecialchars($_GET['taille_visiteur'] ?? '1.20') ?>" required style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 700; background: #fff;">
            </div>

            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; font-size: 0.9rem;">Priorité de la journée :</label>
                <select name="priorite" style="width: 100%; padding: 0.8rem; border: 3px solid var(--noir); font-family: var(--police-texte); font-weight: 700; background: #fff;">
                    <option value="attente">Moins d'attente possible</option>
                    <option value="sensations">Maximum de sensations</option>
                </select>
            </div>

            <button type="submit" style="background: var(--cyan); color: var(--noir); padding: 0.9rem 1.5rem; border: 3px solid var(--noir); font-weight: 900; text-transform: uppercase; box-shadow: 4px 4px 0 var(--noir); cursor: pointer;">
                Générer mon parcours →
            </button>
        </form>
    </div>

    <!-- Résultat du parcours optimisé -->
    <div style="background: var(--fond-carte); border: 4px solid var(--noir); box-shadow: 8px 8px 0 var(--noir); padding: 2rem;">
        <h2 style="font-family: 'Archivo Black', Impact, sans-serif; text-transform: uppercase; font-size: 1.3rem; margin-top: 0; border-bottom: 3px solid var(--noir); padding-bottom: 10px;">
            🚀 Votre Ordre de Visite Recommandé
        </h2>

        <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem;">
            <!-- Exemple d'étape générée dynamiquement depuis PHP -->
            <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background: var(--violet); color: #fff; font-family: 'Archivo Black', Impact, sans-serif; width: 40px; height: 40px; border: 2px solid var(--noir); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">1</div>
                    <div>
                        <h3 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0; font-size: 1.1rem;">Le Trou Portable</h3>
                        <p style="margin: 0.2rem 0 0 0; font-weight: 700; font-size: 0.85rem; color: #555;">Univers : Le Chapiteau / Famille</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="background: var(--vert); border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">⏱️ 15 min d'attente</span>
                    <span style="background: #fff35c; border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">📏 Requis : 0,90m</span>
                </div>
            </div>

            <!-- Étape 2 -->
            <div style="background: #fff; border: 3px solid var(--noir); padding: 1.2rem; box-shadow: 4px 4px 0 var(--noir); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="background: var(--violet); color: #fff; font-family: 'Archivo Black', Impact, sans-serif; width: 40px; height: 40px; border: 2px solid var(--noir); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">2</div>
                    <div>
                        <h3 style="font-family: 'Archivo Black', Impact, sans-serif; margin: 0; font-size: 1.1rem;">L’Enclume Express</h3>
                        <p style="margin: 0.2rem 0 0 0; font-weight: 700; font-size: 0.85rem; color: #555;">Univers : Studio 2D / Aventure</p>
                    </div>
                </div>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <span style="background: var(--vert); border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">⏱️ 20 min d'attente</span>
                    <span style="background: #fff35c; border: 2px solid var(--noir); padding: 0.4rem 0.8rem; font-weight: 800; font-size: 0.85rem;">📏 Requis : 1,10m</span>
                </div>
            </div>
        </div>
    </div>

</div>

<?php 
$content = ob_get_clean();
require 'views/base.php';
?>