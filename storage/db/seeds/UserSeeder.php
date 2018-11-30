<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'status'    => 'N',
                'username'    => 'root',
                'password' => "qW7F0J13cheR/0rg3GBbmX66Q8poD4081aU=",
                'issuer'    => 'http://dealerappcenter.com',
                'tokenPassword'    => 'VmVkZUlOYjZ2Y2s4NUl4b1llR21sUT09',
                'tokenId'    => '719fe5cfdcc161c91ad63b'
            ]
        ];

        $posts = $this->table('users');
        $posts->insert($data)
            ->save();

    }
}
