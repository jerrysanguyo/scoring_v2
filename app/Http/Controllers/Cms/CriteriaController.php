<?php

namespace App\Http\Controllers\Cms;

use App\Models\Cms\Criteria;
use App\Http\Requests\CmsRequest;
use App\DataTables\CmsDataTable;
use App\Services\CmsServices;
use App\Http\Controllers\Controller;

class CriteriaController extends Controller
{
    protected CmsServices $cmsService;
    protected string $resource = 'Criteria';

    public function __construct()
    {
        $this->cmsService = new CmsServices(Criteria::class);
    }

    public function index(CmsDataTable $dataTable)
    {
        $page_title = 'Criteria';
        $resource = $this->resource;
        $columns = ['id', 'name', '# of participants', 'remarks', 'Action'];
        $data = Criteria::getAllCriterias();

        return $dataTable
            ->render('cms.index', compact(
                'page_title',
                'columns',
                'data',
                'resource',
                'dataTable'
            ));
    }
    
    public function store(CmsRequest $request)
    {
        $store = $this->cmsService->cmsStore($request->validated());

        return $this->cmsService->handleRedirect($store, $this->resource, 'created');
    }
    
    public function update(CmsRequest $request, Criteria $criteria)
    {
        $update = $this->cmsService->cmsUpdate($request->validated(), $criteria->id);

        return $this->cmsService->handleRedirect($update, $this->resource, 'updated');
    }
    
    public function destroy(Criteria $criteria)
    {
        $destroy = $this->cmsService->cmsDestroy($criteria->id);

        return $this->cmsService->handleRedirect($destroy, $this->resource, 'deleted');
    }
}
