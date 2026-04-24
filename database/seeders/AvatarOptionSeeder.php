<?php

namespace Database\Seeders;

use App\Models\AvatarOption;
use Illuminate\Database\Seeder;

class AvatarOptionSeeder extends Seeder
{
    public function run(): void
    {
        $avatars = [
            'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png',
            'https://framerusercontent.com/images/xujOvWlIH4jCpEHwRSO8fL3AZyM.png',
            'https://framerusercontent.com/images/voEeLI8QvLxIBheChMgZpIZDBDw.png',
            'https://framerusercontent.com/images/P6B3UqKPpI7pUX8hpOGEuB7DoYI.png',
            'https://framerusercontent.com/images/ZOmjcnCegPgIJe774bHLeiqGoRY.png',
            'https://framerusercontent.com/images/aH4TSB4QigZBUovRTOzJbNfmE8.png',
            'https://framerusercontent.com/images/vjNbwG6wtp9Zat3QDbEDG5SQ8nc.png',
            'https://framerusercontent.com/images/yLvdWmXt1qfpzFexvRPL1YPjEM.png',
            'https://framerusercontent.com/images/L0MgVueQuuaTbIG2RDygjv6nxw.png',
            'https://framerusercontent.com/images/cyZY6rN0VQ2rTCXAp8vDkwwfs.png',
            'https://framerusercontent.com/images/avsgw3MlrBZ7Qemx2LDUzfksapA.png',
            'https://framerusercontent.com/images/tNsNvr6rtFzILJLih7KTMe4uM.png',
            'https://framerusercontent.com/images/QHfWARUm32FA9v9bgBIZeTDFaB8.png',
            'https://framerusercontent.com/images/epaiBXj1vYRcJ7bEafNzHniJ8gQ.png',
            'https://framerusercontent.com/images/yMGFyp1B3WEQPWVHuC2AOcqCwBk.png',
            'https://framerusercontent.com/images/AW5gsxnLBvhE7bhUymsSWpcAP0.png',
            'https://framerusercontent.com/images/0pOGEkOl3QOA0AOhql07dLjouU.png',
            'https://framerusercontent.com/images/Xdax07q3fD8YGG6qtDgZOZEaqI.png',
            'https://framerusercontent.com/images/rMJN1hMOPP8cSGd8LdmmlMesy8.png',
            'https://framerusercontent.com/images/iyDK7k3FedurGjdTkG1KSJYm8no.png',
            'https://framerusercontent.com/images/Ryq4xjuMhGxgQzm7NiX6xlq3938.png',
            'https://framerusercontent.com/images/YCyyVb7j8C5U4vDHPAPqKNHIfAc.png',
            'https://framerusercontent.com/images/CD114mrqzRMe6TQ3ieg8ZQxUk.png',
            'https://framerusercontent.com/images/7wRDToSNM4pfN5ZhkkMQRo4zuY.png',
        ];

        foreach ($avatars as $index => $url) {
            AvatarOption::updateOrCreate(
                ['image_url' => $url],
                [
                    'name' => 'Avatar ' . ($index + 1),
                    'link_url' => $url,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
