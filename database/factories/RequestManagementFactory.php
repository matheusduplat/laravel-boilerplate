<?php

namespace Database\Factories;

use App\Domains\Employee\Model\Employee;
use App\Domains\RequestManagement\Enums\StatusProcedureRequestManagement;
use App\Domains\RequestManagement\Enums\StatusRequestManagement;
use App\Domains\RequestManagement\Enums\TypeRequestManagement;
use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RequestManagementFactory extends Factory
{
    protected $model = RequestManagement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(TypeRequestManagement::cases());

        if ($type->name == TypeRequestManagement::CONSULTATION->name) {
            return [
                "code" => "SO",
                "type" =>  $type->name,
                "note_customer" => fake()->text(),
                "location_performing_procedure" => fake()->optional()->text(),
                "status" => fake()->randomElement(StatusRequestManagement::cases()),
                "status_procedure" => fake()->optional()->randomElement(StatusProcedureRequestManagement::cases()),
                "date_close" => fake()->optional()->dateTimeBetween('+1 month', '+2 months'),
                "code_guide" => fake()->optional()->uuid(),
            ];
        }



        return [
            "code" => "SO",
            "type" =>  $type->name,
            "note_customer" => fake()->text(),
            "location_performing_procedure" => fake()->optional()->text(),
            "attachment" => $type->name != TypeRequestManagement::CONSULTATION->name ? fake()->uuid() : null,
            "code_guide" => fake()->optional()->uuid(),
            "status" => fake()->randomElement(StatusRequestManagement::cases()),
            "status_procedure" => fake()->optional()->randomElement(StatusProcedureRequestManagement::cases()),
            "date_close" => fake()->optional()->dateTimeBetween('+1 month', '+2 months'),
        ];
    }

    /**
     * State para adicionar arquivo real se não for "consulta"
     */
    public function withRealFile(string $disk = 'public', ?string $realPath = null)
    {
        return $this->state(function (array $item) use ($realPath, $disk) {
            if ($item['type'] !== TypeRequestManagement::CONSULTATION->name) {
                // pega o arquivo real (padrão pode ser um arquivo de testes na pasta storage/app/test-files)
                $filePath = $realPath ?? public_path('fake/boleto.pdf');

                $file = new File($filePath);

                $customerId = $attributes['customer_id'] ?? null;
                if (!$customerId && isset($attributes['customer'])) {
                    $customerId = $attributes['customer']->id;
                }

                if (!$customerId) {
                    $customerId = Str::uuid();
                }
                // copia para o disco configurado
                $stored = Storage::disk($disk)->putFileAs(
                    "{$customerId}/request_management/attachment",
                    $file,
                    $file->getFilename()
                );

                // atualiza no banco
                $item['attachment'] = $stored;
            }
            return $item;
        });
    }
    public function responsible()
    {
        return $this->afterCreating(function (RequestManagement $requestManagement) {
            $employee = Employee::factory()->user()->count(2)->create()->pluck('id')->toArray();

            $requestManagement->auditAttach('responsible', $employee);
        });
    }
    public function configure()
    {
        return $this->afterCreating(function ($request) {
            $request->updateQuietly([
                'code' => "SO{$request->created_at->format('Y.m')}.{$request->id}",
            ]);
        });
    }
}
