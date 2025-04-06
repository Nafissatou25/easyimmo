<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
//use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
//use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('rooms')
            ->add('bedrooms')
            ->add('floor')
            ->add('price')
            ->add('heat', ChoiceType::class, [
                'choices' => $this->getChoices() 
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'required'=>false,
                'choice_label' => 'name',
                'multiple' =>true
            ])
            ->add('imageFile', FileType::class, [
                'required' =>false
            ])
            ->add('city')
            ->add('address')
            ->add('postal_code')
            ->add('sold')
            
        ; // ->add('city',null, ['label' =>'Ville']) ca va remplacer city par vill sur le formulair
    }      //'choices' => Property::HEAT remplacé par la method crée getchoice car HEAT renvoi 0 ou 1 au niveau de la selection

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]); 
        }// 'translation_domain' => 'forms' ajoutée pour la traduction des labels
            // ensuite on part dans translation creer le ficher quon a appelé forms.fr.yaml 
            // ensuite dans config/packages/translation.yaml on change local "en" en "fr"

    public function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }


        }
