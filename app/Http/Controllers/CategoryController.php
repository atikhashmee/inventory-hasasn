<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Controllers\AppBaseController;
use App\Models\Category;
use Illuminate\Http\Request;
use Flash;
use Response;

class CategoryController extends AppBaseController
{
    /**
     * Display a listing of the Category.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        /** @var Category $categories */
        $categories = Category::with(['nested'=>function($q) {
            $q->select('categories.*', 'P.total_product');
            $q->leftJoin(\DB::raw('(SELECT COUNT(*) as total_product, category_id FROM products GROUP BY category_id) AS P'), 'P.category_id', '=', 'categories.id');
        }, 'nested.nested'=>function($q){
            $q->select('categories.*', 'P.total_product');
            $q->leftJoin(\DB::raw('(SELECT COUNT(*) as total_product, category_id FROM products GROUP BY category_id) AS P'), 'P.category_id', '=', 'categories.id');
        }])
        ->select('categories.*', 'P.total_product')
        ->leftJoin(\DB::raw('(SELECT COUNT(*) as total_product, category_id FROM products GROUP BY category_id) AS P'), 'P.category_id', '=', 'categories.id')
        ->where('categories.parent_id', 0)
        ->get();
        return view('admin.categories.index')
            ->with('categories', $categories);
    }

    /**
     * Show the form for creating a new Category.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param CreateCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        if ($request->parent_id == -1) {
            $input['parent_id'] = 0;
        }
        /** @var Category $category */
        $category = Category::create($input);

        Flash::success('Category saved successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Display the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Category $category */
        $category = Category::find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified Category.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var Category $category */
        $category = Category::find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        return view('admin.categories.edit')->with('category', $category);
    }

    /**
     * Update the specified Category in storage.
     *
     * @param int $id
     * @param UpdateCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCategoryRequest $request)
    {
        /** @var Category $category */
        $category = Category::find($id);

        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        $category->fill($request->all());
        $category->save();

        Flash::success('Category updated successfully.');

        return redirect(route('admin.categories.index'));
    }

    /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Category $category */
        $category = Category::withCount(['products'])->where('id', $id)->first();
        if (empty($category)) {
            Flash::error('Category not found');

            return redirect(route('admin.categories.index'));
        }

        if ($category->products_count > 0) {
            Flash::error('Category can not be deleted, It has products associated');

            return redirect(route('admin.categories.index'));
        }

        $category->delete();

        Flash::success('Category deleted successfully.');

        return redirect(route('admin.categories.index'));
    }
}
