<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Yowl API Documentation</title>

    <!-- Swagger UI CSS via CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui.css" />
</head>

<body>
    <div id="swagger-ui"></div>

    <!-- Swagger UI JS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@4/swagger-ui-standalone-preset.js"></script>

    <script>
        window.onload = function() {
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{{ url('/docs/api-docs.json') }}",
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
                docExpansion: "list",
                deepLinking: true,
                filter: true,
                persistAuthorization: true,
            });

            window.ui = ui;
        }
    </script>
</body>

</html>

