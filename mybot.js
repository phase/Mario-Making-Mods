const Discord = require("discord.js");
const client = new Discord.Client();
const sql = require("sqlite");
sql.open("./score.sqlite");

client.on('ready', () => {
  client.user.setPresence({ status: 'online', game: { name: 'https://mariomods.net/ - Mario Making Mods' } });
})

const prefix = "!";
client.on("message", message => {
  if (message.author.bot) return;

  if (message.content.startsWith(prefix + "ping")) {
    message.channel.send("pong!");
  }

  sql.get(`SELECT * FROM scores WHERE userId ="${message.author.id}"`).then(row => {
	  if (message.channel.type !== "text") return;
    if (!row) {
      sql.run("INSERT INTO scores (userId, points, level) VALUES (?, ?, ?)", [message.author.id, 1, 0]);
    } else {
      let curLevel = Math.floor(0.1 * Math.sqrt(row.points + 1));
      if (curLevel > row.level) {
        row.level = curLevel;
        sql.run(`UPDATE scores SET points = ${row.points + 1}, level = ${row.level} WHERE userId = ${message.author.id}`);
        message.reply(`You've leveled up to level **${curLevel}**! Ain't that dandy?`);
      }
      sql.run(`UPDATE scores SET points = ${row.points + 1} WHERE userId = ${message.author.id}`);
    }
  }).catch(() => {
	if (message.channel.type !== "text") return;
    console.error;
    sql.run("CREATE TABLE IF NOT EXISTS scores (userId TEXT, points INTEGER, level INTEGER)").then(() => {
      sql.run("INSERT INTO scores (userId, points, level) VALUES (?, ?, ?)", [message.author.id, 1, 0]);
    });
  });

  if (!message.content.startsWith(prefix)) return;

  if (message.content.startsWith(prefix + "level")) {
    sql.get(`SELECT * FROM scores WHERE userId ="${message.author.id}"`).then(row => {
      if (!row) return message.reply("Your current level is 0");
      message.reply(`Your current level is ${row.level}`);
    });
  } else

  if (message.content.startsWith(prefix + "points")) {
    sql.get(`SELECT * FROM scores WHERE userId ="${message.author.id}"`).then(row => {
      if (!row) return message.reply("sadly you do not have any points yet!");
      message.reply(`you currently have ${row.points} points, good going!`);
    });
  } else

  if (message.content.startsWith(prefix + "help")) {
      message.author.send(`!help shows the help menu (sent to DM)
	  !points shows how many points you have. Points are determined on how much you post.
	  !level shows your level. Level is determined on how many points you have.
	  !avatar will send an image of your discord avatar.
	  !invite will give you an invite to our server, with a server description as well as a link to the forums.`);
  }
  
  if (message.content.startsWith(prefix + "avatar")) {
    // Send the user's avatar URL
    message.reply(message.author.avatarURL);
  }
  
  if (message.content.startsWith(prefix + "invite")) {
    // Send a channel invite
	message.channel.send({embed: {
    color: 3447003,
    title: "Mario Making Mods",
    url: "https://mariomods.net",
    description: "We are a community dedicated to bringing you a home for the hacking of Super Mario Maker (Wii U and 3DS). Our community is a place for everyone to post their projects, get help and support, and find tutorials. Join us to share your very own creations, or feel free to just browse the site and check out some of the awesome projects and tutorials found here.",
    fields: [{
        name: "Super Mario Maker Mods",
        value: "We have all sorts of crazy mods for Super Mario Maker, ranging from Super Mario Odyssey in 8-bit style, to the new 32-bit series of custom themes. Whatever you are interested in, you might find it on our Depot. Over at the [Depot](https://mariomods.net/depot), we have download links to some of the best mods out there for Super Mario Maker & a great tutorial for newcomers. It is connected with the forum, so you can check both out."
      },
      {
        name: "PointlessMaker Developement",
        value: "Over at Mario Making Mods, we are currently developing a PC level editor for Super Mario Maker. As Pointless as it sounds, not only is it Actively Developed by our main local moderator [MasterVermilli0n](https://mariomods.net/profile/5-aboood40091), it can be used for Tile Manipulation, ability to chat with others while making levels, tileset manipulation (we have a [custom theme](https://mariomods.net/thread/382-long-grass) that takes advantage of that feature) and so much more. It is currently open sourced on [Github](https://github.com/aboood40091/PointlessMaker), so you can commit some code changes if you'd like."
      },
      {
        name: "Wiki",
        value: "At the Mario Making Mods [Wiki](https://mariomods.net/wiki), you'll find lots of up-to-date information about all the technical parts of Super Mario Maker. You can submit your own findings there, too! You'll also find out how Super Mario ReMaker runs and works."
      },
      {
        name: "Chat",
        value: "Come take a look at our [Discord Server](https://discord.gg/btQdJNw)! It's a great central for quick and general discussions. At the Discord Server, we have many active members from our forums, who love to help people!"
      }
    ],
    timestamp: new Date(),
    footer: {
      icon_url: client.user.avatarURL,
	  text: "Mario Making Mods · by  NightYoshi370, StapleButter & others"
    }
  }
});
  }


});

client.on('guildMemberAdd', member => {
  // Send the message to a designated channel on a server:
  const channel = member.guild.channels.find('name', 'general');
  // Do nothing if the channel wasn't found on this server
  if (!channel) return;
  // Send the message, mentioning the member
  channel.send(`${member}: Welcome to Mario Making Mods, the main Super Mario Maker hacking network! Please read the FAQ before engaging in our conversations: https://mariomods.net/faq`);
});

client.login("Fuck no");