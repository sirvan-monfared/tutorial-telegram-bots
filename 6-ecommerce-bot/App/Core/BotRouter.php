<?php

namespace App\Core;


use App\Models\Command;
use App\Models\User;
use App\Services\TelegramService;
use App\Services\UserService;

class BotRouter {

    protected array $routes = [];

    public function __construct(protected TelegramService $telegram, protected ?User $user = null)
    {

    }

    public function add(string $type,string  $command, $controller, $method, $name = null): static
    {
        $this->routes[] = [
            'type' => $type,
            'command' => $command,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null,
            'name' => $name
        ];

        return $this;
    }

    public function exact($command, $controller, $method, $name = null)
    {
        $this->add('exact', $command, $controller, $method, $name);
    }

    public function startsWith($command, $controller, $method, $name = null)
    {
        $this->add('starts_with', $command, $controller, $method, $name);
    }

    public function after($command, $controller, $method, $name = null)
    {
        $this->add('after', $command, $controller, $method, $name);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key; 
    }


    public function match()
    {
        $user_command = $this->telegram->text();
        $latest_stored_command = $this->user->latestActiveCommand();

        foreach($this->routes as $route) {
            $type = $route['type'];
            $command = $route['command'];
            $controller = $route['controller'];
            $method = $route['method'];

            if ($type === 'exact' && $user_command === $command) {
                return (new $controller)->init($this->telegram, $this->user)->$method($user_command);
            }

            if ($type === 'starts_with' && str_starts_with($user_command, $command)) {
                $param = str_replace($command, '', $user_command);

                return (new $controller)->init($this->telegram, $this->user)->$method($user_command, $param);
            }

            if ($type === 'after' && $latest_stored_command?->command === $command) {
                return (new $controller)->init($this->telegram, $this->user)->closeCommand($latest_stored_command)->$method($user_command);
            }
        }
    }


    public function routes(): array
    {
        return $this->routes;
    }

}