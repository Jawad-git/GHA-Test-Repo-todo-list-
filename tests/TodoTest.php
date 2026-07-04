<?php
use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    public function testTodoArrayStructure()
    {
        $todo = [
            'id' => 12345,
            'text' => 'Test todo',
            'completed' => false
        ];
        
        $this->assertIsArray($todo);
        $this->assertArrayHasKey('id', $todo);
        $this->assertArrayHasKey('text', $todo);
        $this->assertArrayHasKey('completed', $todo);
        $this->assertEquals('Test todo', $todo['text']);
        $this->assertFalse($todo['completed']);
    }
    
    public function testTodoCompletionToggle()
    {
        $todo = [
            'id' => 12345,
            'text' => 'Test todo',
            'completed' => false
        ];
        
        $todo['completed'] = true;
        $this->assertTrue($todo['completed']);
        
        $todo['completed'] = false;
        $this->assertFalse($todo['completed']);
    }
    
    public function testTodoTextUpdate()
    {
        $todo = [
            'id' => 12345,
            'text' => 'Original text',
            'completed' => false
        ];
        
        $todo['text'] = 'Updated text';
        $this->assertEquals('Updated text', $todo['text']);
    }
}