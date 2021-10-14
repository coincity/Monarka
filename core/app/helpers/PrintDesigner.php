<?php 
class PrintDesigner {
    public function __construct($documentWidth=74, $documentHeight=350) {
        $this->documentWidth = $documentWidth;
        $this->columns = 37;

        $this->pdf = new FPDF('P','mm', array($this->documentWidth ,$documentHeight));;
        $this->pdf->SetMargins(5,5);

        
        $this->pdf->AddPage();
        $this->setDefaultFont();
    }   


    public function setBaseFontSize($fontSizeBase=8) {
        $this->fontSizeBase = $fontSizeBase;
    }


    public function setLargeFont() {
        $this->fontSize = $this->fontSizeBase * 3;
        $this->pdf->SetFontSize($this->fontSize);
    }

    public function setMediumFont() {
        $this->fontSize = $this->fontSizeBase * 2;
        $this->pdf->SetFontSize($this->fontSize);
    }
    
    public function setSmallFont() {
        $this->fontSize = $this->fontSizeBase * 1;
        $this->pdf->SetFontSize($this->fontSize);
    }

    public function setNormal() {
        $this->pdf->SetFont('Courier','');
    }

    public function setBold() {
        $this->pdf->SetFont('Courier','B');
    }

    public function setDefaultFont() {
        
        $this->setBaseFontSize();
        $this->setNormal();
        $this->setSmallFont();
    }
    
    public function addLine($text='', $align="L") {
        $this->pdf->Cell(0,$this->fontSize/2,substr($text, 0, $this->columns), 0, 0, $align);
        $this->pdf->Ln();
    }

    public function addDataRow($values, $columnSettings=[]) {
        $stringResult = "";
        if(count($columnSettings) == 0) {
            for ($i=0; $i < count($values); $i++) { 
                $columnSettings[] = $this->columns/count($values).',L';
            }
        } 

        $freeColumnSize = $this->columns;
        $definedSizes = 0;
        for ($i=0; $i < count($columnSettings); $i++) { 
            $columnSetting = explode(",",$columnSettings[$i]);
            $columnSize = !isset($columnSetting[1]) || $columnSetting[1] == "" ? 0 : $columnSetting[1];
            if($columnSize > 0) {
                $freeColumnSize = $freeColumnSize - $columnSize;
                $definedSizes++;
            }
        }
        
            
        for ($i=0; $i < count($columnSettings); $i++) { 
            $columnSetting = explode(",",$columnSettings[$i]);

            $columnSize = !isset($columnSetting[1]) || $columnSetting[1] == "" ? $freeColumnSize/(count($values)-$definedSizes) : $columnSetting[1];
            $columnAlignment = !isset($columnSetting[0]) || $columnSetting[0] == "" ? "L" : $columnSetting[0];
            

            $value = substr($values[$i], 0, $columnSize);
            $padType = ($columnAlignment == 'L' ? STR_PAD_RIGHT : ($columnAlignment == 'C' ? STR_PAD_BOTH : STR_PAD_LEFT));
            $stringResult .= str_pad($value, $columnSize, " ", $padType);
        }
        $this->addLine($stringResult);
    }

    public function addDivider() {
        $this->addLine(str_repeat("-",$this->columns));
    }

    public function end() {
        $this->pdf->Output();
    }
}

?>