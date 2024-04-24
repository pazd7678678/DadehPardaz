<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Info(
 *   version="1.0.0",
 *   title="Mofid NGO API",
 *   description="Mofid NGO API Documentation"
 * )
 *
 * @OA\Tag(name="Auth", description="Authentication & Authorization")
 * @OA\Tag(name="Base", description="Base Information Management")
 * @OA\Tag(name="Payment", description="Payment Management")
 *
 * @OA\Server(url=L5_SWAGGER_CONST_HOST)
 *
 * @OA\SecurityScheme(
 *   type="http",
 *   description="Use auth/login to get the JWT token",
 *   name="Authorization",
 *   in="header",
 *   scheme="bearer",
 *   bearerFormat="jwt",
 *   securityScheme="auth"
 * )
 */
abstract class ApiController extends Controller
{
    public static array $statuses = [
        Response::HTTP_CONTINUE,
        Response::HTTP_SWITCHING_PROTOCOLS,
        Response::HTTP_PROCESSING,
        Response::HTTP_EARLY_HINTS,
        Response::HTTP_OK,
        Response::HTTP_CREATED,
        Response::HTTP_ACCEPTED,
        Response::HTTP_NON_AUTHORITATIVE_INFORMATION,
        Response::HTTP_NO_CONTENT,
        Response::HTTP_RESET_CONTENT,
        Response::HTTP_PARTIAL_CONTENT,
        Response::HTTP_MULTI_STATUS,
        Response::HTTP_ALREADY_REPORTED,
        Response::HTTP_IM_USED,
        Response::HTTP_MULTIPLE_CHOICES,
        Response::HTTP_MOVED_PERMANENTLY,
        Response::HTTP_FOUND,
        Response::HTTP_SEE_OTHER,
        Response::HTTP_NOT_MODIFIED,
        Response::HTTP_USE_PROXY,
        Response::HTTP_RESERVED,
        Response::HTTP_TEMPORARY_REDIRECT,
        Response::HTTP_PERMANENTLY_REDIRECT,
        Response::HTTP_BAD_REQUEST,
        Response::HTTP_UNAUTHORIZED,
        Response::HTTP_PAYMENT_REQUIRED,
        Response::HTTP_FORBIDDEN,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_METHOD_NOT_ALLOWED,
        Response::HTTP_NOT_ACCEPTABLE,
        Response::HTTP_PROXY_AUTHENTICATION_REQUIRED,
        Response::HTTP_REQUEST_TIMEOUT,
        Response::HTTP_CONFLICT,
        Response::HTTP_GONE,
        Response::HTTP_LENGTH_REQUIRED,
        Response::HTTP_PRECONDITION_FAILED,
        Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
        Response::HTTP_REQUEST_URI_TOO_LONG,
        Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
        Response::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE,
        Response::HTTP_EXPECTATION_FAILED,
        Response::HTTP_I_AM_A_TEAPOT,
        Response::HTTP_MISDIRECTED_REQUEST,
        Response::HTTP_UNPROCESSABLE_ENTITY,
        Response::HTTP_LOCKED,
        Response::HTTP_FAILED_DEPENDENCY,
        Response::HTTP_TOO_EARLY,
        Response::HTTP_UPGRADE_REQUIRED,
        Response::HTTP_PRECONDITION_REQUIRED,
        Response::HTTP_TOO_MANY_REQUESTS,
        Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE,
        Response::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS,
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_NOT_IMPLEMENTED,
        Response::HTTP_BAD_GATEWAY,
        Response::HTTP_SERVICE_UNAVAILABLE,
        Response::HTTP_GATEWAY_TIMEOUT,
        Response::HTTP_VERSION_NOT_SUPPORTED,
        Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL,
        Response::HTTP_INSUFFICIENT_STORAGE,
        Response::HTTP_LOOP_DETECTED,
        Response::HTTP_NOT_EXTENDED,
        Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED,
    ];

    /**
     * @param mixed|null $data
     * @param String|null $transformer
     * @param string|null $message
     * @param int $status
     * @return JsonResponse
     */
    public function successPaginated(LengthAwarePaginator $data, ?string $transformer = null, ?string $message = null, int $status = Response::HTTP_OK): JsonResponse
    {
        if (!is_null($transformer)) {
            $realData = $this->transform($data->getCollection(), $transformer);
        } else {
            $realData = $data->getCollection();
        }
        $data = json_decode($data->toJson(), true);
        unset($data['data']);
        $meta = $data;
        $data = $realData;
        return $this->success($data, $meta, $message, $status);
    }

    /**
     * @param Collection|Model $data
     * @param string $resourceClassName
     * @param array $params
     * @return array
     */
    public function transform(Collection|Model $data, string $resourceClassName, array $params = []): array
    {
        if ($data instanceof Collection) {
            $result = [];
            foreach ($data as $datum) {
                $result[] = new $resourceClassName($datum, ...$params);
            }
        } else {
            $result = json_decode(json_encode(new $resourceClassName($data, ...$params)), true);
        }
        return $result;
    }

    /**
     * @param mixed|null $data
     * @param mixed|null $meta
     * @param string|null $message
     * @param int $status
     * @return JsonResponse
     */
    public function success(mixed $data = null, mixed $meta = null, ?string $message = null, int $status = Response::HTTP_OK): JsonResponse
    {
        if (is_array($data)) {
            if (is_null($meta) && isset($data['meta'])) {
                $meta = $data['meta'];
            }
            if (isset($data['data'])) {
                $data = $data['data'];
            }
        }
        $result = $data;
        unset($data);
        $errors = null;
        if (is_array($meta) && !empty($meta)) {
            $this->filterMeta($meta);
        }
        $this->setDefaultMessageIfEmpty($message, $status);
        return response()->json(compact('result', 'meta', 'message', 'errors'), in_array($status, self::$statuses) ? $status : Response::HTTP_OK);
    }

    /**
     * @param array $meta
     * @return void
     */
    private function filterMeta(array &$meta): void
    {
        $keepItems = ['current_page', 'from', 'last_page', 'per_page', 'to', 'total'];
        foreach (array_keys($meta) as $key) {
            if (!in_array($key, $keepItems)) {
                unset($meta[$key]);
            }
        }
        if (empty($meta)) {
            $meta = null;
        } else {
            ksort($meta);
        }
    }

    /**
     * @param string|null $message
     * @param int|string $status
     * @return void
     */
    private function setDefaultMessageIfEmpty(?string &$message, int|string $status): void
    {
        if (trim($message) === '') {
            if (in_array($status, range(200, 299))) {
                $message = 'Operation was successful';
            } elseif (in_array($status, range(300, 399))) {
                $message = 'Redirected';
            } elseif (in_array($status, range(400, 499))) {
                $message = 'Bad Request';
            } elseif (in_array($status, range(500, 599))) {
                $message = 'Internal Server Error';
            } else {
                $message = 'Unknown Error';
            }
        }
    }


    /**
     * @param string $message
     * @param array $errors
     * @param int|string $status
     * @return JsonResponse
     */
    public function error(string $message = 'Unknown error', array $errors = [], int|string $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $result = null;
        $meta = null;
        foreach ($errors as $key => $value) {
            if (!is_array($value)) {
                $errors[$key] = [$value];
            }
        }
        if (empty($errors)) {
            $errors = null;
        }
        $this->setDefaultMessageIfEmpty($message, $status);
        return response()->json(compact('result', 'meta', 'message', 'errors'), in_array($status, self::$statuses) ? $status : Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @param array $fields
     * @return array
     */
    public function sanitize(Request $request, array $fields): array
    {
        $data = array_filter($this->sanitizeInput($request, $fields), fn($value) => trim($value) !== '');
        foreach ($fields as $key => $value) {
            if (trim($value) !== '') {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    /**
     * @param Request $request
     * @param array $fields
     * @return array
     */
    private function sanitizeInput(Request $request, array $fields): array
    {
        $data = $request->all();
        $inputAsArray = [];
        $fieldsWithDefaultValue = [];
        foreach ($fields as $key => $value) {
            if (is_string($key)) {
                $fieldsWithDefaultValue[$key] = $value;
                Arr::set($inputAsArray, $key, $value);
            } else {
                Arr::set($inputAsArray, $value, true);
            }
        }
        $data = $this->recursiveArrayIntersectKey($data, $inputAsArray);
        foreach ($fieldsWithDefaultValue as $key => $value) {
            $data = Arr::add($data, $key, $value);
        }
        return $data;
    }

    /**
     * @param array $referenceArray
     * @param array $checkArray
     * @return array
     */
    private function recursiveArrayIntersectKey(array $referenceArray, array $checkArray): array
    {
        $referenceArray = array_intersect_key($referenceArray, $checkArray);
        foreach ($referenceArray as $key => &$value) {
            if (is_array($value) && is_array($checkArray[$key])) {
                $value = $this->recursiveArrayIntersectKey($value, $checkArray[$key]);
            }
        }
        return $referenceArray;
    }
}
