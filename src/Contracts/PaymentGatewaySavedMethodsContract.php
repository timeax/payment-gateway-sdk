<?php declare(strict_types=1);

namespace PayKit\Contracts;

use Timeax\ConfigSchema\Support\ConfigBag;
use PayKit\Payload\Common\SavedMethod;
use PayKit\Payload\Requests\AttachMethodRequest;
use PayKit\Payload\Requests\DetachMethodRequest;
use PayKit\Payload\Requests\GetMethodRequest;
use PayKit\Payload\Requests\ListMethodsRequest;
use PayKit\Payload\Requests\SetDefaultMethodRequest;
use PayKit\Payload\Responses\SavedMethodList;

interface PaymentGatewaySavedMethodsContract
{
    public function listMethods(ListMethodsRequest $request, ?ConfigBag $config = null): SavedMethodList;

    public function getMethod(GetMethodRequest $request, ?ConfigBag $config = null): ?SavedMethod;

    public function attachMethod(AttachMethodRequest $request, ?ConfigBag $config = null): SavedMethod;

    public function detachMethod(DetachMethodRequest $request, ?ConfigBag $config = null): bool;

    public function setDefaultMethod(SetDefaultMethodRequest $request, ?ConfigBag $config = null): bool;
}


