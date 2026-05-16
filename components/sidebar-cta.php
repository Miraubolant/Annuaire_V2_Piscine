<?php
// $commune  (array)
// $vudCat   (int)
$vudCat ??= VUD_CATEGORIE_ID;
?>
<aside style="position:sticky;top:88px;display:flex;flex-direction:column;gap:16px;">

    <!-- Widget devis -->
    <?php
    $villeName = $commune['nom'] ?? 'votre ville';
    $context   = 'ville';
    require __DIR__ . '/cta-devis.php';
    ?>

    <!-- Aides rapides -->
    <div style="background:var(--cream);border:1px solid var(--stone);border-radius:var(--radius);padding:18px;">
        <h3 style="font-weight:700;color:var(--forest);font-size:13px;margin-bottom:12px;">✅ Aides disponibles</h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                TVA <strong style="color:var(--text);margin-left:3px;">5,5%</strong> isolation thermique
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                Prime CEE jusqu'à <strong style="color:var(--text);margin-left:3px;">2 500 €</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                Éco-PTZ jusqu'à <strong style="color:var(--text);margin-left:3px;">50 000 €</strong>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-muted);">
                <span style="color:var(--forest);font-weight:700;">✓</span>
                <strong style="color:var(--text);">MaPrimeRénov'</strong> selon revenus
            </div>
        </div>
    </div>
</aside>

