<?php

namespace App\Domain\Cons\Validator;

use App\Domain\Cons\Data\ConsFewData;
use App\Domain\Cons\Data\ConsCreatorData;
use Selective\Validation\ValidationResult;
use App\Repository\QueryFactory;
/**
 * Validator.
 */
final class ConsValidator
{
    /**
     * Validate.
     *
     * @param ConsCreatorData $user The user
     *
     * @return ValidationResult The validation result
     */

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    private $repository;
    /**
     * Constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    // public function dayOfWeek():array
    // {
        
    //     return 
    // }

    public function validateCons(ConsCreatorData $cons): ValidationResult
    {
        $validation = new ValidationResult();

        $query = $this->queryFactory->newSelect('consultation')->select('*');

        $results = $query->execute()->fetch('assoc');

        foreach($results as $result){
            if($result["start_hour"].""<=$cons->start_hour && $result["end_hour"]>=$cons->start_hour && $result["accept"]==1 && $result["start_date"]==$cons->start_date){
                $validation->addError('start_hour', __("O tej godzinie sa inne konsultacje"));
            }
            if($result["start_hour"].""<=$cons->end_hour && $result["end_hour"]>=$cons->start_hour && $result["accept"]==1 && $result["start_date"]==$cons->start_date){
                $validation->addError('end_hour', __("O tej godzinie sa inne konsultacje"));
            }
        }

        if (filter_var($cons->email, FILTER_VALIDATE_EMAIL) === false) {
            $validation->addError('email', __('Invalid email address'));
        }

        $query = $this->queryFactory->newSelect('day')->select('*');
        $id_day= $query->execute()->fetchAll('assoc');
        
        switch(sizeof($id_day)){
            case 1:
                if (date('w',strtotime(($cons->start_date))) != $id_day[0]['id_day']) {
                    $validation->addError('id_day', __("W tym dniu nie ma konsultacji"));
                }
            break;
            case 2:
                if ((date('w',strtotime(($cons->start_date))) != $id_day[0]['id_day']) && (date('w',strtotime(($cons->start_date))) != $id_day[1]['id_day'])) {
                    $validation->addError('id_day', __("W tym dniu nie ma konsultacji"));
                }
            break;
        }
            
        return $validation;
    }
    public function validateCons1(ConsFewData $cons): ValidationResult
    {
        $validation = new ValidationResult();

        // if (filter_var($cons->email, FILTER_VALIDATE_EMAIL) === false) {
        //     $validation->addError('email', __('Invalid email address'));
        // }

        $query = $this->queryFactory->newSelect('day')->select('*');
        $id_day= $query->execute()->fetchAll('assoc');
        
        switch(sizeof($id_day)){
            case 1:
                if (date('w',strtotime(($cons->start_date))) != $id_day[0]['id_day']) {
                    $validation->addError('id_day', __("W tym dniu nie ma konsultacji"));
                }
            break;
            case 2:
                if ((date('w',strtotime(($cons->start_date))) != $id_day[0]['id_day']) && (date('w',strtotime(($cons->start_date))) != $id_day[1]['id_day'])) {
                    $validation->addError('id_day', __("W tym dniu nie ma konsultacji"));
                }
            break;
        }
            
        return $validation;
    }
}
