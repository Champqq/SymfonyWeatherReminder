<?php

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestHandler
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function handle(Request $request, string $dtoClass): object
    {
        $data = $request->isXmlHttpRequest() || str_starts_with($request->headers->get('Content-Type') ?? '', 'application/json')
            ? json_decode($request->getContent(), true)
            : $request->request->all();

        if (!is_array($data)) {
            throw new BadRequestHttpException('Invalid request data');
        }

        $dto = new $dtoClass();

        foreach ($data as $key => $value) {
            if (property_exists($dto, $key)) {
                $dto->$key = 'on' === $value ? true : $value;
            }
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getPropertyPath().': '.$error->getMessage();
            }
            throw new BadRequestHttpException(implode("\n", $messages));
        }

        return $dto;
    }
}
