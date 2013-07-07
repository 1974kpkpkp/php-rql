<?php

class TransformationsTest extends TestCase
{
    public function run()
    {
        $this->requireDataset('Heroes');
    
        // FIXME: These checks don't actually verify the ordering (and the ones we specify here as reference are wrong)...
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy(array('combatPower', 'compassionPower'))->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman'));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy(array(r\Desc('combatPower'), r\Desc('compassionPower')))->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman'));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy(array(r\Asc('combatPower'), r\Asc('compassionPower')))->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman'));
            
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->skip(1)->map(r\row('superhero')),
            array('Spiderman', 'Wolverine'));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->limit(1)->map(r\row('superhero')),
            array('Iron Man'));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->nth(1)->attr('superhero'),
            'Spiderman');
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->nth(-1)->attr('superhero'),
            'Wolverine');
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->slice(1)->map(r\row('superhero')),
            array('Spiderman', 'Wolverine'));
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->orderBy('superhero')->slice(1, 1)->map(r\row('superhero')),
            array('Spiderman'));
            
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->pluck('superhero')->union(r\expr(array(array('superhero' => 'foo'))))->map(r\row('superhero')),
            array('Iron Man', 'Wolverine', 'Spiderman', 'foo'));
            
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->withFields(array('superhero', 'nemesis'))->count(), 0.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->withFields('superhero')->count(), 3.0);
        
        $this->checkQueryResult(r\expr(array('a','b','c'))->indexesOf('c'), array(2));
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->isEmpty(), false);
        $this->checkQueryResult(r\expr(new r\ArrayDatum(array()))->isEmpty(), true);
        
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->sample(1)->count(), 1.0);
        $this->checkQueryResult(r\db('Heroes')->table('marvel')->sample(3)->count(), 3.0);
    }
}

?>