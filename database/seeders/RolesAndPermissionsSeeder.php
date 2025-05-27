<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // Nettoyage
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Désactiver temporairement les contraintes FK
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vider les tables Spatie
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();

        // Réactiver les contraintes FK
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Définir toutes les permissions
        $permissions = [

            // Courses
            'course.create', 'course.edit', 'course.delete', 'course.publish', 'course.unpublish', 'course.view', 
            'course.validate.practice-submission',

            // Users
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.assign-role', 'user.suspend', 'user.invite',

            // Roles & Permissions
            'role.create', 'role.edit', 'role.delete', 'role.assign', 'permission.manage',

            // Payments
            'payment.view', 'payment.validate', 'payment.refund', 'payment.configure-gateway',

            // Reports
            'report.view.courses', 'report.view.users', 'report.view.revenue', 'report.view.engagement',

            // Settings
            'settings.manage.general', 'settings.manage.email', 'settings.manage.payouts', 'settings.manage.localization', 
            'settings.manage.ai-tools',

            // AI Tools
            'ai.access.content-generator', 'ai.access.quiz-suggester', 'ai.access.title-suggester', 'ai.use.transcription',

            // Media
            'media.upload', 'media.edit', 'media.delete', 'media.view-library',

            // Quiz & Certifications
            'quiz.create', 'quiz.edit', 'quiz.delete', 'quiz.grade',
            'certificate.issue', 'certificate.verify', 'certificate.blockchain-publish',

            // Community
            'comment.view', 'comment.delete', 'forum.create-thread', 'forum.moderate', 'feedback.read', 'feedback.respond',

            // Remix & Localization
            'content.remix', 'content.translate', 'region.configure', 'map.view.local-skills',

            // Partnerships
            'partner.access.dashboard', 'partner.manage-sponsored-courses', 'partner.track-impact',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Création des rôles et attribution des permissions

        Role::create(['name' => 'super-admin'])->givePermissionTo(Permission::all());

        Role::create(['name' => 'admin'])->givePermissionTo([
            // Users & Roles
            'user.view', 'user.create', 'user.edit', 'user.delete', 'user.assign-role', 'user.suspend',
            'role.assign', 'role.create', 'role.edit', 'role.delete',
            'permission.manage',

            // Courses, Payments, Reports
            'course.view', 'course.publish', 'course.unpublish',
            'payment.view', 'payment.validate', 'payment.refund',
            'report.view.courses', 'report.view.users', 'report.view.revenue', 'report.view.engagement',

            // Settings
            'settings.manage.general', 'settings.manage.email', 'settings.manage.payouts', 'settings.manage.localization',
        ]);

        Role::create(['name' => 'instructor'])->givePermissionTo([
            'course.create', 'course.edit', 'course.delete', 'course.publish', 'course.view',
            'quiz.create', 'quiz.edit', 'quiz.delete', 'quiz.grade',
            'media.upload', 'media.edit', 'media.delete',
            'report.view.courses', 'certificate.issue', 'certificate.verify',
            'ai.access.content-generator', 'ai.access.quiz-suggester', 'ai.access.title-suggester',
        ]);

        Role::create(['name' => 'student'])->givePermissionTo([
            'course.view', 'quiz.grade', 'certificate.verify',
            'comment.view', 'forum.create-thread',
        ]);

        Role::create(['name' => 'moderator'])->givePermissionTo([
            'comment.view', 'comment.delete',
            'forum.moderate', 'feedback.read', 'feedback.respond'
        ]);

        Role::create(['name' => 'content-editor'])->givePermissionTo([
            'course.edit', 'media.edit', 'content.translate', 'content.remix'
        ]);

        Role::create(['name' => 'partner'])->givePermissionTo([
            'partner.access.dashboard', 'partner.manage-sponsored-courses', 'partner.track-impact',
            'report.view.users', 'report.view.revenue'
        ]);
    }
}
