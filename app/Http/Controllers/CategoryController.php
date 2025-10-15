<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Lister toutes les catégories",
     *     description="Récupérer la liste de toutes les catégories disponibles",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="error", type="null"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Category")),
     *             @OA\Property(property="message", type="string", example="Liste des catégories récupérée")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 200,
            'error' => null,
            'data' => $categories,
            'message' => 'Liste des catégories récupérée',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Afficher une catégorie spécifique",
     *     description="Récupérer les détails d'une catégorie par son ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="error", type="null"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="message", type="string", example="Catégorie trouvée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/StandardResponse")
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'status' => 200,
                'error' => null,
                'data' => $category,
                'message' => 'Catégorie trouvée',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'error' => 'Catégorie non trouvée',
                'data' => (object)[],
                'message' => 'La catégorie demandée n\'existe pas',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/categories",
     *     tags={"Categories"},
     *     summary="Créer une nouvelle catégorie",
     *     description="Ajouter une nouvelle catégorie au système",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technologie", description="Nom unique de la catégorie"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Catégorie pour les projets technologiques", description="Description optionnelle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=201),
     *             @OA\Property(property="error", type="null"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="message", type="string", example="Catégorie créée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name',
                'description' => 'nullable|string',
            ]);
            $category = Category::create($validated);
            return response()->json([
                'status' => 201,
                'error' => null,
                'data' => $category,
                'message' => 'Catégorie créée avec succès',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage(),
                'data' => (object)[],
                'message' => 'Erreur de validation',
            ], 400);
        }
    }

    /**
     * @OA\Put(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Mettre à jour une catégorie",
     *     description="Modifier les informations d'une catégorie existante",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie à modifier",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Technologie Avancée", description="Nom unique de la catégorie"),
     *             @OA\Property(property="description", type="string", nullable=true, example="Description mise à jour", description="Description optionnelle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="error", type="null"),
     *             @OA\Property(property="data", ref="#/components/schemas/Category"),
     *             @OA\Property(property="message", type="string", example="Catégorie mise à jour")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationError")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/StandardResponse")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|unique:categories,name,' . $id,
                'description' => 'nullable|string',
            ]);
            $category->update($validated);
            return response()->json([
                'status' => 200,
                'error' => null,
                'data' => $category,
                'message' => 'Catégorie mise à jour',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 400,
                'error' => $e->getMessage(),
                'data' => (object)[],
                'message' => 'Erreur de validation',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'error' => 'Catégorie non trouvée',
                'data' => (object)[],
                'message' => 'La catégorie à mettre à jour n\'existe pas',
            ], 404);
        }
    }

    /**
     * @OA\Delete(
     *     path="/categories/{id}",
     *     tags={"Categories"},
     *     summary="Supprimer une catégorie",
     *     description="Supprimer définitivement une catégorie",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la catégorie à supprimer",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="error", type="null"),
     *             @OA\Property(property="data", type="object"),
     *             @OA\Property(property="message", type="string", example="Catégorie supprimée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(ref="#/components/schemas/StandardResponse")
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return response()->json([
                'status' => 200,
                'error' => null,
                'data' => (object)[],
                'message' => 'Catégorie supprimée',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 404,
                'error' => 'Catégorie non trouvée',
                'data' => (object)[],
                'message' => 'La catégorie à supprimer n\'existe pas',
            ], 404);
        }
    }

    // ========================================
    // MÉTHODES ADMIN AVEC PAGINATION
    // ========================================

    /**
     * Liste paginée des catégories pour l'admin
     */
    public function adminIndex(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');

            $query = Category::withCount('posts');

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            $categories = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => $categories,
                'message' => 'Liste des catégories récupérée'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'data' => null,
                'message' => 'Erreur lors de la récupération des catégories'
            ], 500);
        }
    }

    /**
     * Afficher une catégorie pour l'admin
     */
    public function adminShow($id)
    {
        try {
            $category = Category::withCount('posts')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => ['category' => $category],
                'message' => 'Catégorie récupérée'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => 'Catégorie non trouvée',
                'data' => null,
                'message' => 'La catégorie demandée n\'existe pas'
            ], 404);
        }
    }

    /**
     * Créer une nouvelle catégorie pour l'admin
     */
    public function adminStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string|max:1000',
            ]);

            $category = Category::create($validatedData);

            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => ['category' => $category],
                'message' => 'Catégorie créée avec succès'
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->errors(),
                'data' => null,
                'message' => 'Erreur de validation'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'data' => null,
                'message' => 'Erreur lors de la création de la catégorie'
            ], 500);
        }
    }

    /**
     * Mettre à jour une catégorie pour l'admin
     */
    public function adminUpdate(Request $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string|max:1000',
            ]);

            $category->update($validatedData);

            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => ['category' => $category],
                'message' => 'Catégorie mise à jour avec succès'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->errors(),
                'data' => null,
                'message' => 'Erreur de validation'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'data' => null,
                'message' => 'Erreur lors de la mise à jour de la catégorie'
            ], 500);
        }
    }

    /**
     * Supprimer une catégorie pour l'admin
     */
    public function adminDestroy($id)
    {
        try {
            $category = Category::withCount('posts')->findOrFail($id);

            // Vérifier s'il y a des posts dans cette catégorie
            if ($category->posts_count > 0) {
                // Optionnel : déplacer les posts vers une catégorie par défaut
                // ou laisser l'admin gérer cela
                $category->posts()->update(['category_id' => null]);
            }

            $category->delete();

            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => null,
                'message' => 'Catégorie supprimée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'data' => null,
                'message' => 'Erreur lors de la suppression de la catégorie'
            ], 500);
        }
    }

    /**
     * Statistiques des catégories pour l'admin
     */
    public function adminStats()
    {
        try {
            $stats = [
                'total_categories' => Category::count(),
                'categories_with_posts' => Category::has('posts')->count(),
                'categories_without_posts' => Category::doesntHave('posts')->count(),
                'most_used_categories' => Category::withCount('posts')
                    ->orderBy('posts_count', 'desc')
                    ->limit(5)
                    ->get(),
            ];

            return response()->json([
                'status' => 'success',
                'error' => null,
                'data' => $stats,
                'message' => 'Statistiques des catégories récupérées'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'data' => null,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }
}
