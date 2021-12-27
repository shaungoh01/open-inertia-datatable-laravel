<?php

namespace Shaun\OpenInertiaDatatableLaravel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;

class Datatable
{
    private Model $model;
    public function __construct(Model $model){
        $this->model = $model;
    }
    // must pass in all belongsTo Relation
    public function datatableQuery(Builder $builder, Array $relations): Builder
    {
        $model = $this->model;
        $tableName = $model->getTable();

        if(Schema::hasColumn($tableName,request('sort'))){
            $builder->orderBy(request('sort'),request('order'));
            return $builder;
        }

        foreach($relations as $relation){
            if(method_exists($model,$relation) && request()->has('sort') && request()->has('order') ){
                if(explode('_',request('sort'))[0] == $relation){
                    $relationship = $model->$relation();
                    $relationTableName = $relationship->getRelated()->getTable();
                    $foreignKey = $relationship->getQualifiedForeignKeyName();
                    $ownerKey = $model->getOwnerKey($relationship);
                    $colName = $model->getColName(request('sort'));
                    $builder->leftJoin($relationTableName,$foreignKey,'=',$ownerKey)
                            ->addSelect($tableName.'.id as id')
                            ->addSelect($relationTableName.'.id as '.$relationTableName.'_id')
                            ->orderBy($colName,request('order'));
                }
            }
        }
        return $builder;
    }
    
    private function getOwnerKey($relationship)
    {
        if(method_exists($relationship,'getQualifiedOwnerKeyName')){
            return $relationship->getQualifiedOwnerKeyName();
        }else{
            return $relationship->getQualifiedParentKeyName();
        }
    }

    private function getColName($sort)
    {
        $exploded = explode('_',$sort);
        array_shift($exploded);
        return implode('_',$exploded);
    }
}