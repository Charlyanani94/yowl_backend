<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signalement non retenu - MAKERS Community</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #e97d00 0%, #2563eb 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .info { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .post-info { background: white; padding: 15px; border-radius: 6px; margin: 15px 0; border: 1px solid #e5e7eb; }
        .guidelines { background: #ede9fe; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> Signalement Examiné</h1>
            <p>MAKERS Community - Modération</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $reporterName }}</strong>,</p>
            
            <div class="info">
                <strong> Votre signalement a été examiné mais n'a pas été retenu.</strong>
            </div>
            
            <p>Nous avons soigneusement examiné votre signalement et, après analyse, nous avons déterminé que le contenu respecte nos règles communautaires.</p>
            
            <div class="post-info">
                <h3> Détails du signalement :</h3>
                <p><strong>Post signalé :</strong> "{{ $postTitle }}"</p>
                <p><strong>Raison :</strong> {{ ucfirst($reportReason) }}</p>
                <p><strong>Date du signalement :</strong> {{ $reportDate }}</p>
                @if($adminNote)
                <p><strong>Explication de l'administrateur :</strong> {{ $adminNote }}</p>
                @endif
            </div>
            
            <div class="guidelines">
                <h3>Rappel de nos règles :</h3>
                <ul>
                    <li><strong>Spam :</strong> Contenu répétitif ou publicitaire non sollicité</li>
                    <li><strong>Inapproprié :</strong> Contenu offensant, vulgaire ou déplacé</li>
                    <li><strong>Harcèlement :</strong> Attaques personnelles ou comportement agressif</li>
                    <li><strong>Fake :</strong> Informations délibérément fausses ou trompeuses</li>
                </ul>
            </div>
            
            <p>
                <strong>Merci de votre vigilance :</strong><br>
                Même si ce signalement n'a pas été retenu, votre participation à la modération communautaire est précieuse. 
                Continuez à signaler tout contenu qui vous semble problématique.
            </p>
            
            <p>Si vous avez des questions sur cette décision, n'hésitez pas à contacter notre équipe de support.</p>
        </div>
        
        <div class="footer">
            <p>YOWL Community © 2025 - Équipe de modération</p>
            <p>Cet email est automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>