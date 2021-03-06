<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateModelosRequest;
use App\Http\Requests\UpdateModelosRequest;
use App\Models\Marcas;
use App\Repositories\ModelosRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class ModelosController extends AppBaseController
{
    /** @var  ModelosRepository */
    private $modelosRepository;

    public function __construct(ModelosRepository $modelosRepo)
    {
        $this->modelosRepository = $modelosRepo;
    }

    /**
     * Display a listing of the Modelos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->modelosRepository->pushCriteria(new RequestCriteria($request));
        $modelos = $this->modelosRepository->all();

        return view('modelos.index')
            ->with('modelos', $modelos);
    }

    /**
     * Show the form for creating a new Modelos.
     *
     * @return Response
     */
    public function create(){
        $marcasObjets = Marcas::all();

        $marcas=array();
        foreach ($marcasObjets as $m){
            $marcas[$m->id]=$m->descripcion;
        }
        return view('modelos.create',compact('marcas'));
    }

    /**
     * Store a newly created Modelos in storage.
     *
     * @param CreateModelosRequest $request
     *
     * @return Response
     */
    public function store(CreateModelosRequest $request)
    {
        $input = $request->all();

        $modelos = $this->modelosRepository->create($input);

        Flash::success('Modelos grabado exitosamente.');

        return redirect(route('modelos.index'));
    }

    /**
     * Display the specified Modelos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $modelos = $this->modelosRepository->findWithoutFail($id);

        if (empty($modelos)) {
            Flash::error('Modelos not found');

            return redirect(route('modelos.index'));
        }

        return view('modelos.show')->with('modelos', $modelos);
    }

    /**
     * Show the form for editing the specified Modelos.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $modelos = $this->modelosRepository->findWithoutFail($id);

        if (empty($modelos)) {
            Flash::error('Modelos not found');

            return redirect(route('modelos.index'));
        }

        $marcasObjets = Marcas::all();

        $marcas=array();
        foreach ($marcasObjets as $m){
            $marcas[$m->id]=$m->descripcion;
        }

        return view('modelos.edit',compact('modelos','marcas'));
    }

    /**
     * Update the specified Modelos in storage.
     *
     * @param  int              $id
     * @param UpdateModelosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateModelosRequest $request)
    {
        $modelos = $this->modelosRepository->findWithoutFail($id);

        if (empty($modelos)) {
            Flash::error('Modelos not found');

            return redirect(route('modelos.index'));
        }

        $modelos = $this->modelosRepository->update($request->all(), $id);

        Flash::success('Modelos updated successfully.');

        return redirect(route('modelos.index'));
    }

    /**
     * Remove the specified Modelos from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $modelos = $this->modelosRepository->findWithoutFail($id);

        if (empty($modelos)) {
            Flash::error('Modelos not found');

            return redirect(route('modelos.index'));
        }

        $this->modelosRepository->delete($id);

        Flash::success('Modelos deleted successfully.');

        return redirect(route('modelos.index'));
    }
}
