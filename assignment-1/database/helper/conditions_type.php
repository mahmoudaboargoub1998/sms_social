<?php
class ConditionsType{
   public const PATTREN = 'PATTREN';
   public const MATCH = 'MATCH';
}

class Conditions{
    private string $type;
    private $value;

    private string $operator;
    public function __construct(string $type,string $operator,$value){
        $this->type = $type;
        $this->operator = $operator;
        $this->value = $value;
    }
    public function getType(): string{
        return $this->type;
    }
    public function getValue(): string{
        return $this->value;
    }
    public function getOperator(): string{
        return $this->operator;
    }
   static public function pattren($value): Conditions {
    return new Conditions(ConditionsType::PATTREN, 'LIKE', $value);
   }
   static public function match($value): Conditions {
    return new Conditions(ConditionsType::MATCH, '=', $value);
   }
   public function isBlank(): bool {
        return !isset($this->value) || strlen(trim($this->value)) === 0;
   }
   public function isNotBlank(): bool {
    // echo $this->isBlank();
        return !$this->isBlank();
   }
    public function condition():string{
        if($this->isBlank()){
            return '';
        }
        switch ($this->type) {
            case ConditionsType::MATCH:
                return "$this->value";
            case ConditionsType::PATTREN:
                return "%$this->value%";
            default:
                return '';
        }
    }
}