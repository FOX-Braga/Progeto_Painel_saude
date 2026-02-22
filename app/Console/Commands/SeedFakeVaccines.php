<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ChildVaccine;
use App\Models\Vaccine;
use App\Models\Child;
use Carbon\Carbon;

class SeedFakeVaccines extends Command
{
    protected $signature = 'app:seed-fake-vaccines';
    protected $description = 'Seed fake applied vaccines for children';

    public function handle()
    {
        $vaccinesMaster = Vaccine::all();
        $children = Child::all();
        $generated = 0;

        // 1. Backfill child_vaccines se não existir
        if ($vaccinesMaster->isNotEmpty()) {
            foreach ($children as $child) {
                if ($child->birth_date) {
                    $birthDate = Carbon::parse($child->birth_date);
                    foreach ($vaccinesMaster as $vaccine) {
                        $dueDate = $birthDate->copy()->addMonths($vaccine->months_due);

                        $exists = ChildVaccine::where('child_id', $child->id)
                            ->where('vaccine_id', $vaccine->id)
                            ->exists();
                        if (!$exists) {
                            ChildVaccine::create([
                                'child_id' => $child->id,
                                'vaccine_id' => $vaccine->id,
                                'due_date' => $dueDate->format('Y-m-d'),
                                'status' => 'pending'
                            ]);
                            $generated++;
                        }
                    }
                }
            }
        }

        $this->info("{$generated} registros base de vacinas criados para crianças antigas.");

        // 2. Falsificar aplicações
        $vaccines = ChildVaccine::all();
        $count = 0;
        $now = Carbon::now();

        foreach ($vaccines as $cv) {
            $dueDate = Carbon::parse($cv->due_date);

            // If due date was in the past, let's randomly apply %80 of them
            if ($dueDate->isPast() && rand(1, 100) <= 80) {
                // Apply date is a bit after due_date but before now
                $daysDiff = $dueDate->diffInDays($now);
                $applyDate = $dueDate->copy()->addDays(rand(0, min(30, max(1, $daysDiff))));

                if ($applyDate->isAfter($now)) {
                    $applyDate = $now->copy()->subDays(rand(1, 5));
                }

                $cv->update([
                    'status' => 'applied',
                    'applied_date' => $applyDate->format('Y-m-d'),
                    'professional' => 'Enf. Juliana Silva',
                    'lot_number' => 'LT' . rand(10000, 99999)
                ]);
                $count++;
            }
        }

        $this->info("Geração finalizada. {$count} vacinas marcadas como aplicadas no histórico.");
    }
}
