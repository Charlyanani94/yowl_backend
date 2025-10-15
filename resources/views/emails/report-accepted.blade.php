<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Signalement validé - YOWL Community</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #e97d00 0%, #2563eb 100%); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .success { background: #dcfce7; border-left: 4px solid #16a34a; padding: 15px; margin: 20px 0; border-radius: 4px; }
        .post-info { background: white; padding: 15px; border-radius: 6px; margin: 15px 0; border: 1px solid #e5e7eb; }
        .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Signalement Validé</h1>
            <p>YOWL Community - Modération</p>
        </div>
        
        <div class="content">
            <p>Bonjour <strong>{{ $reporterName }}</strong>,</p>
            
            <div class="success">
                <strong> Votre signalement a été validé par notre équipe de modération.</strong>
            </div>
            
            <p>Merci d'avoir signalé ce contenu inapproprié. Votre vigilance aide à maintenir la qualité de notre communauté.</p>
            
            <div class="post-info">
                <h3> Détails du signalement :</h3>
                <p><strong>Post signalé :</strong> "{{ $postTitle }}"</p>
                <p><strong>Raison :</strong> {{ ucfirst($reportReason) }}</p>
                <p><strong>Date du signalement :</strong> {{ $reportDate }}</p>
                @if($adminNote)
                <p><strong>Note de l'administrateur :</strong> {{ $adminNote }}</p>
                @endif
            </div>
            
            <p>Des actions appropriées ont été prises concernant ce contenu conformément à nos règles communautaires.</p>
            
            <p>
                <strong>Continuez à nous aider :</strong><br>
                Si vous constatez d'autres contenus problématiques, n'hésitez pas à les signaler. 
                Ensemble, nous construisons une communauté respectueuse et bienveillante.
            </p>
        </div>
        
        <div class="footer">
            <p>YOWL Community © 2025 - Équipe de modération</p>
            <p>Cet email est automatique, merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>