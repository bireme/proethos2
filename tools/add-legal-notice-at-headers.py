#!coding:utf-8

import os

path_can_have = ['/app/', '/fixtures/', '/src/', '/web/']
path_cant_have = ['/vendor/', '/JMS/', '/cache/']

extension_message_php = """
// This file is part of the ProEthos Software. 
// 
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software. 
// 
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://github.com/bireme/proethos2/blob/master/doc/license.md
"""

extension_message_css = """
/*
* This file is part of the ProEthos Software. 
* 
* Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
* ProEthos under the terms of the ProEthos License as published by PAHO, which
* restricts commercial use of the Software. 
* 
* ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
* WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
* PARTICULAR PURPOSE. See the ProEthos License for more details. 
* 
* You should have received a copy of the ProEthos License along with the ProEthos
* Software. If not, see
* https://github.com/bireme/proethos2/blob/master/doc/license.md
*/
"""

for root, dirs, files in os.walk("."):
    for file in files:
        file = os.path.join(root, file)

        continue_with_file = False
        for item in path_can_have:
            if item in file:
                continue_with_file = True

        for item in path_cant_have:
            if item in file:
                continue_with_file = False

        if continue_with_file:
            with open(file) as handle:
                data = handle.read()
            
            if not 'This file is part of the ProEthos Software' in data:
                if file.endswith(".php"):
                    if data.count("<?php") == 1:
                        data = data.replace("<?php", "<?php\n\n%s\n" % extension_message_php.strip())
                        with open(file, 'w') as output:
                            output.write(data)
                    else:
                        print "ERROR: %s" % file

                if file.endswith(".css"):
                    data = "%s\n\n%s" % (extension_message_css, data)
                    with open(file, 'w') as output:
                        output.write(data)
                
                if file.endswith(".js"):
                    data = "%s\n\n%s" % (extension_message_php, data)
                    with open(file, 'w') as output:
                        output.write(data)
