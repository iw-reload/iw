IceWars Reload
==============

Rewrite of the browser game www.icewars.de. Very early development stage. Part of the first milestone - besides a working foundation for the game - is a working resource system and the possibility to construct buildings. More features like research, dependencies between buildings and research, trading system, fleet management and alliances will follow, once the basics prove to be working.

Resources
---------

For modeling, I'm currently using http://genmymodel.com. The current project includes a conceptual data model for the first milestone. It's available at https://repository.genmymodel.com/bwoester/iw-reload.

There's a server running development snapshots of the game. This is strictly a testing environment! It might be reset at any time, it might crash, it might give you all sorts of unexpected results. It also doesn't necessarily showcase the most up-to-date version of the game. This being said, you can give it a try at http://iw-dev.h2322584.stratoserver.net.

Development
-----------

The game is developed using www.yiiframework.com, more specifically its latest version 2, which is a complete rewrite of yii v1. I'm currently also evaluating www.doctrine-project.org as the persistence layer.
