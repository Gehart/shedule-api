<?php

namespace App\Http\Controllers;

use App\Entities\Post;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     */
    public function check(Request $request, EntityManagerInterface $entityManager)
    {
        $a = 4;
        $b = $a;
//        var_dump($b);
//        $connection = pg_connect("host=postgres port=5432 dbname=postgres user=user password=password");

        $post = new Post('test', 'it is a test');
        $entityManager->persist($post);
        $entityManager->flush();


//        if ($connection) {
//            return 'connected to postgresql';
//        } else {
//            return 'there has been an error connecting';
//        }
    }
}
