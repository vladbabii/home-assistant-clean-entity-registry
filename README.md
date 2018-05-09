# Clean home-assistant's entity_registry.yml

If you've added and remove alot of devices in Home Assistant, the *entity_registry.yaml* file can get pretty big with old unused entries.

I noticed that the api/states provided the currently used entities, so I decided to automate this tedious task.

[Download or copy-paste the contents](https://raw.githubusercontent.com/vladbabii/home-assistant-clean-entity-registry/master/regenerate.php) into a regenerate.php file.

Install php-cli (for example on ubuntu just run *apt-get install php-cli*)

Run the script with

```
regenerate.php /home/your-user/.homeassistant/entity_registry.yaml "http://your-home-assistant:8123/api/states" delete > output.yaml
```

now you can inspect the output.yaml and see if everyhing is ok, then replace the original file with this one.

If you do not put "delete" at the end, the unneeded entities will be commented in the output file, instead of being removed.

Enjoy!
