<?php

use App\Entity\Todo;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addGlobal('user', $app['session']->get('user'));

    return $twig;
}));


$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html', [
        'readme' => file_get_contents('README.md'),
    ]);
});


$app->match('/login', function (Request $request) use ($app) {
    $username = $request->get('username');
    $password = $request->get('password');

    if ($username) {
        $sql = "SELECT * FROM users WHERE username = '$username' and password = '$password'";
        $user = $app['db']->fetchAssoc($sql);

        if ($user){
            $app['session']->set('user', $user);
            return $app->redirect('/todo');
        }
    }

    return $app['twig']->render('login.html', array());
});


$app->get('/logout', function () use ($app) {
    $app['session']->set('user', null);
    return $app->redirect('/');
});


$app->get('/todo/{id}', function ($id) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }

    if ($id){
        $em = $app['orm.em'];
        $todo = $em->find(Todo::class, $id);
        return $app->json($todo, 200);
    } else {
        return $app['twig']->render('todos.html');
    }
})
->value('id', null);


$app->get('/todo/{id}/json', function ($id) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }
    if ($id){
        $em = $app['orm.em'];
        $todo = $em->find(Todo::class, $id);
        return $app->json($todo, 200);
    }
});

$app->get('/todos/json', function () use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }
    $user_id = $user['id'];
    $em = $app['orm.em'];
    $user = $em->find(User::class, $user_id);
    return $app->json($user->getTodos()->toArray(), 200);
});

$app->post('/todo/add', function (Request $request) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }
    $user_id = $user['id'];
    $em = $app['orm.em'];
    $user = $em->find(User::class, $user_id);

    $description = $request->get('description');
    $errors = $app['validator']->validate($description, new Assert\NotBlank());
    if (count($errors) > 0) {
        return $app->json(array('message' => 'description is required'), 422);
    } else {
        $todo = new Todo();
        $todo->setUser($user);
        $todo->setDescription($description);
        $em->persist($todo);
        $em->flush();

        return $app->json(array('message' => 'New todo is added'), 201);
    }
});


$app->match('/todo/delete/{id}', function ($id) use ($app) {
    if (null === $user = $app['session']->get('user')) {
        return $app->redirect('/login');
    }

    if($id) {
        $em = $app['orm.em'];
        $todo = $em->find(Todo::class, $id);
        $em->remove($todo);
        $em->flush();

        return $app->json(array('message' => 'Todo is deleted'), 200);
    }
});