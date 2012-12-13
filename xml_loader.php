<?php
include 'vendor/autoload.php';

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

//$encoders = array(new XmlEncoder(), new JsonEncoder());
//$normalizers = array(new GetSetMethodNormalizer());
//
//$serializer = new Serializer($normalizers, $encoders);
$serializer = JMS\Serializer\SerializerBuilder::create()->build();

$start = memory_get_usage();
var_dump(0);
$xml = simplexml_load_file('structure_zf2.xml');
var_dump(memory_get_usage() - $start);
$obj_counter = 0;

$class_index = array();

$project = new \phpDocumentor\Ast\ProjectNode();
$project->name = $xml['title'];

foreach ($xml->file as $file) {
    $file_node = new \phpDocumentor\Ast\FileNode();
    $file_node->hash = (string)$file['hash'];
    $file_node->path = (string)$file['path'];
    $file_node->summary = (string)$file->docblock->description;
    $file_node->description = (string)$file->docblock->{'long-description'};
    $project->files[] = $file_node;
    $obj_counter++;
    foreach ($file->function as $function) {
        $function_node = new \phpDocumentor\Ast\FunctionNode();
        $function_node->name = (string)$function->name;
        $function_node->fqsen = (string)$function->full_name;
        $function_node->summary = (string)$function->docblock->description;
        $function_node->description = (string)$function->docblock->{'long-description'};
        $function_node->line_number = (string)$function['line'];
        $file_node->functions[] = $function_node;
        $obj_counter++;
    }
    foreach ($file->constant as $constant) {
        $constant_node = new \phpDocumentor\Ast\ConstantNode();
        $constant_node->name = (string)$constant->name;
        $constant_node->fqsen = (string)$constant->full_name;
        $constant_node->summary = (string)$constant->docblock->description;
        $constant_node->description = (string)$constant->docblock->{'long-description'};
        $constant_node->line_number = (string)$constant['line'];
        $file_node->constants[] = $constant_node;
        $obj_counter++;
    }
    foreach ($file->class as $class) {
        $class_node = new \phpDocumentor\Ast\ClassNode();
        $class_node->name = (string)$class->name;
        $class_node->fqsen = (string)$class->full_name;
        $class_node->summary = (string)$class->docblock->description;
        $class_node->description = (string)$class->docblock->{'long-description'};
        $class_node->extends = (string)$class->extends;
        $class_node->line_number = (string)$class['line'];
        foreach ($class->implements as $implement) {
            $class_node->implements[] = (string)$implement;
        }
        $file_node->classes[] = $class_node;
        $class_index[$class_node->fqsen] = $class_node;
        $obj_counter++;

        $methods = array();
        foreach ($class->method as $method) {
            $method_node = new \phpDocumentor\Ast\MethodNode();
            $method_node->name = (string)$method->name;
            $method_node->fqsen = (string)$method->full_name;
            $method_node->summary = (string)$method->docblock->description;
            $method_node->description = (string)$method->docblock->{'long-description'};
            $class_node->methods[] = $method_node;
            $obj_counter++;
        }

        $constants = array();
        foreach ($class->constant as $constant) {
            $constant_node = new \phpDocumentor\Ast\ConstantNode();
            $constant_node->name = (string)$constant->name;
            $constant_node->fqsen = (string)$constant->full_name;
            $constant_node->summary = (string)$constant->docblock->description;
            $constant_node->description = (string)$constant->docblock->{'long-description'};
            $class_node->constants[] = $constant_node;
            $obj_counter++;
        }

        foreach ($class->property as $property) {
            $property_node = new \phpDocumentor\Ast\PropertyNode();
            $property_node->name = (string)$property->name;
            $property_node->fqsen = (string)$property->full_name;
            $property_node->summary = (string)$property->docblock->description;
            $property_node->description = (string)$property->docblock->{'long-description'};
            $class_node->properties[] = $property_node;
            $obj_counter++;
        }
    }
    foreach ($file->interface as $interface) {
        $interface_node = new \phpDocumentor\Ast\InterfaceNode();
        $interface_node->name = (string)$interface->name;
        $interface_node->fqsen = (string)$interface->full_name;
        $interface_node->summary = (string)$interface->docblock->description;
        $interface_node->description = (string)$interface->docblock->{'long-description'};
        $interface_node->extends = (string)$interface->extends;
        $file_node->interfaces[] = $interface_node;
        foreach ($interface->method as $method) {
            $method_node = new \phpDocumentor\Ast\MethodNode();
            $method_node->name = (string)$method->name;
            $method_node->fqsen = (string)$method->full_name;
            $method_node->summary = (string)$method->docblock->description;
            $method_node->description = (string)$method->docblock->{'long-description'};
            $interface_node->methods[] = $method_node;
            $obj_counter++;
        }
        $interface_node->line_number = (string)$interface['line'];
        $class_index[$interface_node->fqsen] = $interface_node;
        $obj_counter++;
    }
    foreach ($file->trait as $trait) {
        $trait_node = new \phpDocumentor\Ast\TraitNode();
        $trait_node->name = (string)$trait->name;
        $trait_node->fqsen = (string)$trait->full_name;
        $trait_node->summary = (string)$trait->docblock->description;
        $trait_node->description = (string)$trait->docblock->{'long-description'};
        $file_node->traits[] = $trait_node;
        foreach ($trait->property as $property) {
            $property_node = new \phpDocumentor\Ast\PropertyNode();
            $property_node->name = (string)$property->name;
            $property_node->fqsen = (string)$property->full_name;
            $property_node->summary = (string)$property->docblock->description;
            $property_node->description = (string)$property->docblock->{'long-description'};
            $trait_node->properties[] = $property_node;
            $obj_counter++;
        }
        foreach ($trait->method as $method) {
            $method_node = new \phpDocumentor\Ast\MethodNode();
            $method_node->name = (string)$method->name;
            $method_node->fqsen = (string)$method->full_name;
            $method_node->summary = (string)$method->docblock->description;
            $method_node->description = (string)$method->docblock->{'long-description'};
            $trait_node->methods[] = $method_node;
            $obj_counter++;
        }
        $trait_node->line_number = (string)$trait['line'];
        $class_index[$trait_node->fqsen] = $trait_node;
        $obj_counter++;
    }
}
var_dump(memory_get_usage() - $start);

/** @var \phpDocumentor\Ast\ClassNode $class */
foreach ($class_index as $class) {
    if (isset($class_index[$class->extends])) {
        $class->extends = $class_index[$class->extends];
    }
    if (isset($class->implements)) {
        foreach ((array)$class->implements as $key => $implement) {
            if (isset($class_index[$implement])) {
                $class->implements[$key] = $class_index[$implement];
            }
        }
    }
}

var_dump(memory_get_usage() - $start);
var_dump(memory_get_peak_usage());
var_dump($obj_counter);

//var_dump($project);
$serializer->serialize($project, 'json');

var_dump(memory_get_peak_usage());
