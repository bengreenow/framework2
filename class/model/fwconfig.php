<?php
/**
 * A model class for the RedBean object FWConfig
 *
 * @author Lindsay Marshall <lindsay.marshall@ncl.ac.uk>
 * @copyright 2018 Newcastle University
 *
 */
    namespace Model;
/**
 * A class implementing a RedBean model for Page beans
 */
/**
 * @var string   The type of the bean that stores roles for this page
 */
    class FWConfig extends \RedBeanPHP\SimpleModel
    {
/**
 * @var Array   Key is name of field and the array contains flags for checks
 */
        private static $editfields = [
            'value'       => [TRUE],         # [NOTEMPTY]
            'integrity'   => [FALSE],
            'crossorigin' => [FALSE],
            'type'        => [TRUE],
        ];
/**
 * Add a new FWConfig bean
 *
 * @param object    $context    The context object
 *
 * @return void
 */
        public static function add($context)
        {
            $fdt = $context->formdata();
            $name = $fdt->mustpost('name');
            $bn = \R::findOne('fwconfig', 'name=?', [$name]);
            if (is_object($bn))
            {
                $context->web()->bad();
            }
            $bn = \R::dispense('fwconfig');
            $bn->name = $name;
            $bn->value = $fdt->mustpost('value');
            $bn->local = $fdt->post('local', 0);
            $bn->fixed = 0;
            $bn->integrity = '';
            $bn->defer = 0;
            echo \R::store($bn);
        }
/**
 * Handle an edit form for this fwconfig item
 *
 * @param object   $context    The context object
 *
 * @return  array   [TRUE if error, [error messages]]
 */
        public function edit($context)
        {
            $emess = [];
            $fdt = $context->formdata();
            foreach (self::$editfields as $fld => $flags)
            { // might need more fields for different applications
                $val = $fdt->post($fld, '');
                if ($flags[0] && $val === '')
                { // this is an error as this is a required field
                    $emess = [$fld.' is required'];
                }
                elseif ($val != $this->bean->$fld)
                {
                    $this->bean->$fld = $val;
                }
            }
            \R::store($this->bean);        }
    }
?>