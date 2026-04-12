<?php

namespace CafeHistoire\CafeBlog\Database\Seeders;

use Illuminate\Database\Seeder;
use CafeHistoire\CafeBlog\Models\Post;
use CafeHistoire\CafeBlog\Models\Category;
use CafeHistoire\CafeBlog\Models\Author;

class CafeBlogSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAuthors();
        $this->seedCategories();
        $this->seedPosts();
    }

    protected function seedAuthors(): void
    {
        $authors = [
            [
                'name'              => 'Isabelle Morel',
                'email'             => 'isabelle@cafehistoire.dev',
                'bio'               => 'Historienne spécialisée dans les routes commerciales du café du XVIIe siècle. Auteure de "La Route des Grains".',
                'coffee_preference' => 'Éthiopien naturel, torréfaction légère',
                'origin_specialty'  => 'ethiopie',
            ],
            [
                'name'              => 'Thomas Duval',
                'email'             => 'thomas@cafehistoire.dev',
                'bio'               => 'Barista champion de France 2019, consultant pour des torréfacteurs artisanaux.',
                'coffee_preference' => 'Espresso yéménite Moka',
                'origin_specialty'  => 'yemen',
            ],
            [
                'name'              => 'Amara Diallo',
                'email'             => 'amara@cafehistoire.dev',
                'bio'               => 'Agronome et producteur de café au Kenya. Défenseur du commerce équitable.',
                'coffee_preference' => 'Washed Kenyan AA',
                'origin_specialty'  => 'kenya',
            ],
        ];

        foreach ($authors as $data) {
            Author::firstOrCreate(['email' => $data['email']], $data);
        }
    }

    protected function seedCategories(): void
    {
        foreach (Category::DEFAULT_CATEGORIES as $i => $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                array_merge($cat, ['sort_order' => $i, 'is_active' => true])
            );
        }
    }

    protected function seedPosts(): void
    {
        $author1 = Author::where('email', 'isabelle@cafehistoire.dev')->first();
        $author2 = Author::where('email', 'thomas@cafehistoire.dev')->first();
        $catHistoire = Category::where('name', 'Histoire du Café')->first();
        $catOrigines = Category::where('name', 'Origines & Terroirs')->first();

        $posts = [
            [
                'title'         => 'Kaldi et ses chèvres : la légende éthiopienne du café',
                'excerpt'       => 'Vers 850 apr. J.-C., un berger éthiopien du nom de Kaldi remarqua que ses chèvres dansaient toute la nuit après avoir mangé des baies d\'un arbre inconnu...',
                'content'       => $this->kaldisContent(),
                'coffee_origin' => 'ethiopie',
                'coffee_era'    => 'moyen_age',
                'author_id'     => $author1->id,
                'category_id'   => $catHistoire->id,
                'is_featured'   => true,
                'status'        => 'published',
                'published_at'  => now()->subDays(5),
            ],
            [
                'title'         => 'Les cafés ottomans : l\'invention de la sociabilité moderne',
                'excerpt'       => 'Istanbul, 1554 : deux marchands syriens ouvrent le premier café public, la « Qahveh khaneh ». En quelques décennies, on en compte plus de six cents dans la seule capitale ottomane.',
                'content'       => $this->ottomanContent(),
                'coffee_origin' => 'yemen',
                'coffee_era'    => 'renaissance',
                'author_id'     => $author1->id,
                'category_id'   => $catHistoire->id,
                'is_featured'   => true,
                'status'        => 'published',
                'published_at'  => now()->subDays(12),
            ],
            [
                'title'         => 'Yirgacheffe : le berceau de tous les cafés',
                'excerpt'       => 'Nichée à 1 800 mètres dans le sud de l\'Éthiopie, la région de Yirgacheffe produit des cafés aux notes florales et fruitées uniques au monde — l\'arôme originel du café.',
                'content'       => $this->yirgacheffeContent(),
                'coffee_origin' => 'ethiopie',
                'coffee_era'    => 'contemporaine',
                'author_id'     => $author2->id,
                'category_id'   => $catOrigines->id,
                'is_featured'   => false,
                'status'        => 'published',
                'published_at'  => now()->subDays(3),
            ],
        ];

        foreach ($posts as $data) {
            $slug = \Illuminate\Support\Str::slug($data['title']);
            Post::firstOrCreate(
                ['slug' => $slug],
                array_merge($data, ['slug' => $slug, 'reading_time_minutes' => rand(4, 12)])
            );
        }
    }

    private function kaldisContent(): string
    {
        return <<<HTML
<p>Vers 850 après J.-C., dans les hauts plateaux de Kaffa, en Éthiopie, vivait un jeune berger du nom de Kaldi. 
Un soir, il remarqua quelque chose d'étrange : ses chèvres, habituellement dociles dès la tombée de la nuit, 
bondissaient avec une énergie inhabituelle. Elles avaient brouté les baies rouges d'un arbrisseau qu'il ne connaissait pas.</p>

<h2>Du troupeau au monastère</h2>
<p>Intrigué, Kaldi apporta quelques branches au moine d'un monastère voisin. Sceptique, le religieux jeta 
les baies dans le feu — libérant dans l'air un arôme d'une douceur envoûtante. Les grains torréfiés furent 
récupérés, broyés et dissous dans l'eau chaude. La première tasse de café venait d'être préparée.</p>

<p>Cette légende, transmise oralement pendant des siècles, illustre parfaitement la relation intime entre 
l'Éthiopie et le café. Car si la botanique situe l'origine de <em>Coffea arabica</em> dans les forêts 
montagneuses d'Éthiopie et du Soudan, la légende de Kaldi demeure le récit fondateur de toute une civilisation du café.</p>

<h2>De la baie au breuvage</h2>
<p>Les premières utilisations connues du café en Éthiopie ne ressemblaient pas à ce que nous connaissons aujourd'hui. 
Les tribus Oromo mélangeaient les baies écrasées avec de la graisse animale pour former des boules énergétiques — 
ancêtres improbables de nos barres protéinées. Il faudra attendre le XIVe siècle et les marchands soufis yéménites 
pour que le café soit bu sous forme d'infusion.</p>

<blockquote>
<p>« Le café éveille l'esprit, ouvre les portes de la perception et éloigne la torpeur de l'âme. »<br>
<em>— Al-Jaziri, chroniqueur arabe, XVe siècle</em></p>
</blockquote>

<h2>L'Éthiopie, mère de tous les cafés</h2>
<p>Aujourd'hui encore, l'Éthiopie produit certains des cafés les plus complexes et les plus recherchés au monde. 
Les régions de Yirgacheffe, Sidamo, Harrar et Limu offrent une diversité génétique du caféier sans équivalent, 
fruit de millénaires de croissance sauvage et de sélection naturelle dans les forêts primaires.</p>

<p>La prochaine fois que vous porterez une tasse à vos lèvres, pensez à Kaldi et ses chèvres dansantes. 
C'est de leur nuit d'insomnie joyeuse que tout a commencé.</p>
HTML;
    }

    private function ottomanContent(): string
    {
        return <<<HTML
<p>En 1554, deux marchands syriens, Hakem et Shems, ouvrirent à Constantinople la première <em>qahveh khaneh</em> 
— maison du café. En moins de dix ans, plus de six cents établissements similaires fleurissaient dans la seule 
capitale ottomane.</p>

<h2>Le parlement du peuple</h2>
<p>Les cafés ottomans n'étaient pas de simples débits de boisson. Ils jouaient un rôle politique et social 
fondamental : on y jouait aux échecs, on y débattait de philosophie, on y lisait la presse à voix haute 
pour les analphabètes, on y récitait de la poésie. Le café devint rapidement ce que l'historien Jürgen 
Habermas appellera, quatre siècles plus tard, une « sphère publique ».</p>

<h2>L'inquiétude des sultans</h2>
<p>Cette liberté de parole inquiéta rapidement le pouvoir. Plusieurs sultans tentèrent d'interdire le café, 
le soupçonnant de nourrir la sédition. Selim II le menaça de mort en 1566, Murad IV alla jusqu'à l'exécution 
de contrevenants au XVIIe siècle. Ces interdictions ne durèrent jamais longtemps : le café était déjà 
trop profondément ancré dans la culture ottomane.</p>

<h2>De Constantinople à Vienne</h2>
<p>Les ambassadeurs et marchands vénitiens rapportèrent le café en Europe dès le début du XVIIe siècle. 
Mais c'est le siège de Vienne de 1683 qui donna naissance, selon la légende, au premier café viennois. 
Un soldat polonais, Franz Georg Kolschitzky, aurait récupéré des sacs de café abandonnés par l'armée 
ottomane en retraite pour ouvrir le « Hof zur Blauen Flasche ».</p>

<p>L'Europe n'allait plus jamais se réveiller sans café.</p>
HTML;
    }

    private function yirgacheffeContent(): string
    {
        return <<<HTML
<p>À 1 800 mètres d'altitude, dans la zone de Gedeo au sud de l'Éthiopie, pousse l'un des cafés les plus 
célébrés au monde. Yirgacheffe — que les amateurs prononcent avec révérence — est bien plus qu'une région : 
c'est une expérience sensorielle.</p>

<h2>Profil aromatique</h2>
<p>Un Yirgacheffe de qualité se distingue par ses notes florales intenses (jasmin, bergamote), ses arômes 
fruités (citron, pêche, myrtille) et une acidité lumineuse, presque cristalline. Ce profil unique résulte 
de plusieurs facteurs combinés : altitude, pluviométrie, sol volcanique riche et variétés génétiquement 
sauvages de <em>Coffea arabica</em>.</p>

<h2>Lavé ou naturel ?</h2>
<p>Deux méthodes de traitement s'affrontent à Yirgacheffe. Le <strong>café lavé</strong> (washed) offre une 
tasse propre, transparente, aux arômes floraux très nets. Le <strong>café naturel</strong> (natural), séché 
avec la pulpe, donne des notes de fruits rouges plus sucrées, presque vinées. Les deux méritent l'exploration.</p>

<h2>Un terroir sous pression</h2>
<p>Malgré sa réputation mondiale, le café de Yirgacheffe affronte des défis majeurs : changement climatique, 
déforestation et pression économique sur les petits producteurs. Des organisations comme la Yirgacheffe 
Coffee Farmers' Cooperative Union travaillent à préserver ce patrimoine vivant.</p>

<p>Torréfié légèrement, préparé en V60 ou en Chemex, le Yirgacheffe vous offrira l'une des plus belles 
tasses de votre vie. C'est une promesse.</p>
HTML;
    }
}
