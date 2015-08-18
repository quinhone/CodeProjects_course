<?php

use Illuminate\Database\Seeder;

class ProjectMemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //CodeProject\Entities\ProjectTask::truncate();
        factory(CodeProject\Entities\ProjectMember::class, 10)->create();
    }
}
