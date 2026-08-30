<?php

namespace Tests\Feature;

use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SystemSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_static_get_pages_do_not_return_server_errors(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $routes = collect(Route::getRoutes())->filter(function (IlluminateRoute $route) {
            return in_array('GET', $route->methods(), true)
                && !str_contains($route->uri(), '{')
                && in_array('auth', $route->gatherMiddleware(), true)
                && !str_starts_with($route->uri(), '_');
        });

        $this->assertGreaterThan(30, $routes->count(), 'จำนวน static GET routes น้อยผิดปกติ');

        foreach ($routes as $route) {
            $response = $this->actingAs($admin)->get('/' . ltrim($route->uri(), '/'));
            $this->assertLessThan(
                500,
                $response->getStatusCode(),
                "Route [{$route->getName()}] {$route->uri()} returned {$response->getStatusCode()}"
            );
        }
    }

    public function test_teacher_student_and_guardian_main_pages_do_not_return_server_errors(): void
    {
        $teacher = Teacher::factory()->create();
        $teacherUser = User::factory()->create(['role' => 'teacher', 'teacher_id' => $teacher->id]);
        $this->assertNamedRoutesBelow500($teacherUser, [
            'dashboard', 'teacher.schedule', 'teacher.tasks', 'teacher.students',
            'teaching-logs.index', 'homework-submissions.index', 'run-throughs.index',
            'reschedule-requests.my-index', 'makeup-requests.my-index', 'teacher-leaves.my-index',
            'payroll.my-index', 'notifications.index', 'trial-leads.my-index',
        ]);

        $student = Student::factory()->create();
        $studentUser = User::factory()->create(['role' => 'student', 'student_id' => $student->id]);
        $this->assertNamedRoutesBelow500($studentUser, [
            'dashboard', 'enrollments.my-index', 'schedules.my-index', 'teaching-reports.my-index',
            'homework-submissions.my-index', 'run-throughs.my-index', 'leaves.index',
            'store.index', 'store.my-orders', 'membership.my-index', 'membership.my-points', 'notifications.index',
        ]);

        $guardian = Guardian::create(['full_name' => 'ผู้ปกครองทดสอบ', 'phone' => '0811111111']);
        $guardianUser = User::factory()->create(['role' => 'guardian', 'guardian_id' => $guardian->id]);
        $this->assertNamedRoutesBelow500($guardianUser, [
            'dashboard', 'enrollments.my-index', 'schedules.my-index', 'teaching-reports.my-index',
            'homework-submissions.my-index', 'run-throughs.my-index', 'leaves.index',
            'store.index', 'store.my-orders', 'membership.my-index', 'membership.my-points', 'notifications.index',
        ]);
    }

    private function assertNamedRoutesBelow500(User $user, array $routeNames): void
    {
        foreach ($routeNames as $routeName) {
            $response = $this->actingAs($user)->get(route($routeName));
            $this->assertLessThan(500, $response->getStatusCode(), "Route [{$routeName}] returned {$response->getStatusCode()}");
        }
    }
}
