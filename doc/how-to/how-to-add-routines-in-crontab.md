How to add routines in crontab
==============================

Proethos2 has one routine that sent emails.
This routine need to be added in your crontab. To do this, please insert the rows below in your crontab typing
`crontab -e`, from your linux console. Don't forget to replace <proethos2_path> for your correctly path.

```
00  01  *  *  *  <proethos2_path>/symphony/app/console proethos2:send-monitoring-action-email

```
