<?php
declare(strict_types=1);

namespace App\Controller;

use App\Http\Exception\ValidationException;
use DI\Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

abstract class Controller
{
    protected $c;

    public function __construct(Container $container)
    {
        $this->c = $container;
    }
    
    public function validate(Request $request, array $rules=[])
    {
        $validator = new Validator(
            $params= $request->getParsedBody()
        );

        foreach ($rules as $key=>$value) {
            $validator->mapFieldRules($key, $value);
        }

        if (!$validator->validate()) {
            throw new ValidationException(
                $validator->errors()
            );
        }

        return $params;
    }
}
