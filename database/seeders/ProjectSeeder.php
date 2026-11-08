<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'name' => 'Modern Luxury Villa',
                'name_ar' => 'فيلا فاخرة عصرية',
                'description' => 'A stunning modern villa combining luxury and comfort, featuring open-plan living spaces, elegant finishes, and panoramic views.',
                'description_ar' => 'فيلا حديثة مذهلة تجمع بين الفخامة والراحة، مع مساحات معيشة مفتوحة وتشطيبات أنيقة وإطلالات بانورامية.',
                'thumbnail' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80'
                ],
                'area' => '350 sqm',
                'rooms_count' => 5,
                'project_type' => 'Residential',
                'project_type_ar' => 'سكني',
                'engineering_function' => 'Interior Design',
                'engineering_function_ar' => 'تصميم داخلي',
                'location' => 'New Cairo, Egypt',
                'location_ar' => 'القاهرة الجديدة، مصر',
                'duration' => '6 months',
                'category' => 'Residential Interior Design',
                'category_ar' => 'تصميم داخلي سكني',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Corporate Office Space',
                'name_ar' => 'مساحة مكتبية للشركات',
                'description' => 'A sophisticated corporate headquarters designed for productivity and collaboration, featuring contemporary design elements and smart technology integration.',
                'description_ar' => 'مقر رئيسي للشركات أنيق مصمم للإنتاجية والتعاون، مع عناصر تصميم معاصرة وتكامل تقني ذكي.',
                'thumbnail' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1503387762-592deb58ef4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80'
                ],
                'area' => '800 sqm',
                'rooms_count' => 12,
                'project_type' => 'Commercial',
                'project_type_ar' => 'تجاري',
                'engineering_function' => 'Architecture',
                'engineering_function_ar' => 'هندسة معمارية',
                'location' => 'Nasr City, Egypt',
                'location_ar' => 'مدينة نصر، مصر',
                'duration' => '8 months',
                'category' => 'Commercial Architecture',
                'category_ar' => 'هندسة معمارية تجارية',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Luxury Hotel Lobby',
                'name_ar' => 'ردهة فندق فاخر',
                'description' => 'An elegant hotel lobby that creates a memorable first impression with luxurious materials, sophisticated lighting, and welcoming ambiance.',
                'description_ar' => 'ردهة فندق أنيقة تخلق انطباعًا أوليًا لا يُنسى بالمواد الفاخرة والإضاءة المتطورة والجو الترحيبي.',
                'thumbnail' => 'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1560448204-603b3fc33ddc?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1582719471384-894fbb16e074?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80'
                ],
                'area' => '200 sqm',
                'rooms_count' => null,
                'project_type' => 'Hospitality',
                'project_type_ar' => 'ضيافة',
                'engineering_function' => 'Interior Design',
                'engineering_function_ar' => 'تصميم داخلي',
                'location' => 'Zamalek, Egypt',
                'location_ar' => 'الزمالك، مصر',
                'duration' => '4 months',
                'category' => 'Hospitality Design',
                'category_ar' => 'تصميم الضيافة',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Minimalist Kitchen',
                'name_ar' => 'مطبخ بسيط',
                'description' => 'A clean and functional kitchen design with minimalist aesthetics, maximizing space efficiency while maintaining a modern and elegant look.',
                'description_ar' => 'تصميم مطبخ نظيف وعملي بجمالية بسيطة، يعظم كفاءة المساحة مع الحفاظ على مظهر حديث وأنيق.',
                'thumbnail' => 'https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                'images' => [
                    'https://images.unsplash.com/photo-1556911220-bff31c812dba?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1556912172-45b7abe8b7e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80',
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80'
                ],
                'area' => '25 sqm',
                'rooms_count' => 1,
                'project_type' => 'Residential',
                'project_type_ar' => 'سكني',
                'engineering_function' => 'Interior Design',
                'engineering_function_ar' => 'تصميم داخلي',
                'location' => 'Maadi, Egypt',
                'location_ar' => 'المعادي، مصر',
                'duration' => '2 months',
                'category' => 'Residential Design',
                'category_ar' => 'تصميم سكني',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
