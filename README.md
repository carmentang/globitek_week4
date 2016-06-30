# Project 4 - Globitek Authentication and Login Throttling

Time spent: **X** hours spent in total

## User Stories

The following **required** functionality is completed:

On the existing pages "public/staff/users/new.php" and "public/staff/users/edit.php", a user should see:
- [x] A form which includes a text input for "Password" and "Confirm Password" which do not display the password in plain text as it is being typed.
- [x] Text which reads "Passwords should be at least 12 characters and include at least one uppercase letter, lowercase letter, number, and symbol."

For both users/new.php and users/edit.php, submitting the form performs data validations:
- [x] Returns an error if password or confirm_password are blank.
- [x] Returns an error if password and confirm_password do not match.
- [x] Returns an error if password is not at least 12 characters long.
- [x] Returns an error if password does not contain at least one of each: uppercase letter, lowercase letter, number, symbol.
- [x] Returns any errors related to other validations already on the user.

If all validations on the user data pass:
- [x] It encrypts the password with PHP's password_hash() function. (Tips)
- [x] It stores the password in the database as users.hashed_password in the same SQL statement which creates/updates the user record.

Notice that login and logout pages already exist ("public/staff/login.php" and "public/staff/logout.php") and have the CSRF tokens and other protections added last week.
- [x] Currently, the login page is using a $master_password variable so that all user passwords are the same password (which is terrible security). Remove it from the code. Then use PHP's password_verify() function to test the entered password against the password stored in users.hashed_password for the username provided.
- [x] Ensure the login page does not display content which would create a User Enumeration weakness.

If a user fails to log in:
- [x] Record the failed login in "failed_logins" for the first 5 attempts.
- [x] After 5 failed attempts, their next attempt should return a message which says "Too many failed logins for this username. You will need to wait X minutes before attempting another login."
- [ Change back from 20 secs ] If users try again during the lockout period—for example, one minute later—the message will decrease the number of minutes remaining. It will always round fractional minutes upward. (Because rounding either way would make it say "0 minutes" when there were 29 seconds left.)
- [x] After a lockout period has past, the next attempt (successful or not) will reset the failed_logins.count to 0.

After any successful login:
- [x] Set the failed_logins.count for the username to 0.

- [x] Watch out for SQLI Injection and Cross-Site Scripting vulnerabilities. There should not be any existing vulnerabilities in the code. Make sure you did not introduce any vulnerabilities while accomplishing the previous objectives.


The following advanced user stories are optional:

- [x] Bonus 1: Even if all of the page content and error messages are exactly the same, the login page does have a subtle Username Enumeration weakness. Identify the weakness and write a short description of how the code could be modified to be more secure. Include your answer in the README file that accompanies your assignment submission.
-> One factor that could have been easily glossed over was the User Enumeration weakness--because in the specs
it said to say "Too many failed logins for this username" when the user has reached their limit of 5 login
attempts. Initially, my code only said "Login unsuccessful" for any invalid user, but a malicious individual
can easily figure out valid users by attempting different passwords for a given user and figure out if it is
valid or not through the error messages. Hence, I allowed for invalid users to also be added into the failed_logins
database so that it would generate the same message.
- [x] Bonus 2: On "public/staff/users/edit.php", it is no longer possible to edit a user without also editing their password because the password input cannot be blank. Instead, modify the code in validate_user() to only run the password validations if the password is not blank. Next, modify update_user so that it only encrypts the password and updates users.hashed_password with the result if the password is not blank. In other words, a blank password will still allow updating other user values and not touch the existing password, but providing a password will validate and update the password too.
- [x] Bonus 3: password_hash() allows configuration options to be a passed in as arguments. Use the options to set the bcrypt "cost" parameter to 11 (the default is 10). Be sure to change it for both the insert and update actions. Before you make the change, create a new user using the default cost (10). After you make the change, try to login as that user again. It will still succeed even though a hash with cost 10 and cost 11 return different values. How is this possible? Include your answer in the README file that accompanies your assignment submission.
-> PHP parses anything that starts with a $ inside double quotes as a variable. Hence, they only look at what's after the $10$ or $11$. In that case, it doesn't matter if we use cost of 10 or 11 since it isn't taken into account when verifying
- [x] Bonus 4: On "public/staff/users/edit.php", add a new text field for "Previous password". It should not display the password in plain text as it is being typed. When the form is submitted, validate that the correct password has been provided before allowing the password to be updated. If not, it should return the validation message: "Previous password is incorrect". If you also completed Bonus Objective 2, then it should only require the correct previous password if the new password is being updated.
- [x] Advanced 1: Pretend that password_hash() and password_verify() do not exist. (They were added to PHP in 2013.) Implement these same functions yourself using the PHP function crypt() and the bcrypt hash algorithm. Name your versions my_password_hash() and my_password_verify() and include them in "private/auth_functions.php". Be sure to include a custom salt value of 22 characters like the PHP functions do.
- [x] Advanced 2: Write a PHP function in "private/auth_functions.php" called generate_strong_password() which will generate a random strong password containing the number of characters specified be a function argument. On the new and edit user pages add the text: "Strong password suggestion: ". Then use your function to create a strong password with 12 characters. Every time the page is reloaded the suggestion should change.


## Video Walkthrough


Here's a walkthrough of implemented user stories:

<img src='http://imgur.com/gpjjk71' title='Video Walkthrough' width='' alt='Video Walkthrough' />

GIF created with [LiceCap](http://www.cockos.com/licecap/).

## Notes

The throttling was a bit challenging because I had to ensure that a lot of edge cases were covered--but I learned a lot!

## License

    Copyright [Carmen Tang] [name of copyright owner]

    Licensed under the Apache License, Version 2.0 (the "License");
    you may not use this file except in compliance with the License.
    You may obtain a copy of the License at

        http://www.apache.org/licenses/LICENSE-2.0

    Unless required by applicable law or agreed to in writing, software
    distributed under the License is distributed on an "AS IS" BASIS,
    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
    See the License for the specific language governing permissions and
    limitations under the License.
