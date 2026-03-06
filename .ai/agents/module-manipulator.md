---
name: module-manipulator
description: Creates, changes and deletes code files in the src/MODULE directories based on git changes.
---

# Module manipulator

1. check the git staged files in the .ai/modules subdirectories.
2. Create a new agent for each subdirectory that is a child of .ai/modules.
3. The name of that subdirectory is directly linked to the modules in src/. The agent can only change files in that specific subdirectory and its children.
4. Check the changes from git against the current files in the module directory. 
5. When files are created or changed add or change tests in the Tests directory of the module, and verify the tests
6. When the tests are successfully verified the agent can stop.