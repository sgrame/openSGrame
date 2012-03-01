<?php
class SG_Form_Decorator_SimpleTable extends Zend_Form_Decorator_Abstract
{
    /**
     * Set the table row headers
     * 
     * @param array
     * 
     * @return void
     */
    public function setColumns($labels)
    {
        $this->_options['columns'] = $labels;
    }
  
    public function render($content)
    {
      $element = $this->getElement();
      $view = $element->getView();
      if (null === $view) {
        return $content;
      }
      
      $columns = $this->getOption('columns');
      $class = $this->getOption('class');
      $id = $this->getOption('id');
  
      $columns_html = '';
      foreach ($columns as $current_column_name) {
        $columns_html .= '<th>'.$current_column_name.'</th>';
      }
  
      $result = '
        <table class="'.$class.'" id="'.$id.'">
          <thead>
            <tr>
              '.$columns_html.'
            </tr>
          </thead>
          <tbody>
            '.$content.'
          </tbody>
        </table>
      ';
  
      return $result;
    }
}