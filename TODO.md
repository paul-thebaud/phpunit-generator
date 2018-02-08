* Disable sub directories parsing with config
* Disable protected or private parsing with config
* Remove container factories interfaces
* Backup file before erase them

If global function
```
Company\doSomething();

OR

use function Company\doSomething;

doSomething();
```

If public static
```
Employee::doSomething();
```

If public not static
```
$this->employee->doSomething();
```

If not public static
```
$method = (new \ReflectionClass(Employee::class))
    ->getMethod('doSomething');
$method->setAccessible(true);

$method->invoke(null);
$method->invoke(null, "params1", "params2");
```

If not public not static
```
$method = (new \ReflectionClass(Employee::class))
    ->getMethod('doSomething');
$method->setAccessible(true);

$method->invoke($this->employee);
$method->invoke($this->employee, "params1", "params2");
```