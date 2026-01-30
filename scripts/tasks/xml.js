import fs from 'fs-extra';
import { XMLParser, XMLBuilder } from 'fast-xml-parser';
import { config, stringsreplace } from '../../config.js';
import { getArchiveName, createHashFromFile } from './util.js';

function extend(target, ...sources) {
    sources.forEach(source => {
        for (let prop in source) {
            target[prop] = source[prop];
        }
    });
    return target;
}

const parser = new XMLParser({ ignoreAttributes: false });
const builder = new XMLBuilder({
    ignoreAttributes: false,
    format: true,
    indentBy: '  ', // zwei Leerzeichen
    suppressEmptyNode: false
});

const log = console.log.bind(console);

export const updateXML = async () => {
    try {
        log(`[xml] start: updating ${config.paths.updateXML.src}`);
        // Ursprüngliches Update-XML lesen
        const data = await fs.promises.readFile(config.paths.updateXML.src, 'utf8');
        await fs.promises.writeFile(config.paths.updateXML.rename, data, 'utf8');
        log(`[xml] backup written: ${config.paths.updateXML.rename}`);

        // Template-Update-Node laden
        const templateData = await fs.promises.readFile(config.paths.updateXML.template, 'utf8');
        const templateXml = parser.parse(templateData);
        const updateNode = templateXml.update; // direktes Objekt

        // Ursprüngliches XML parsen
        const xml = parser.parse(data);
        const existingUpdates = Array.isArray(xml.updates.update)
            ? xml.updates.update
            : xml.updates.update
                ? [xml.updates.update]
                : [];

        // Neue Version aus Template extrahieren
        const newVersion = updateNode.version?.toString().trim();

        // Prüfen, ob diese Version bereits existiert
        const versionExists = existingUpdates.some(u => u.version?.toString().trim() === newVersion);

        if (versionExists) {
            log(`[xml] skip: Version ${newVersion} existiert bereits`);
            return; // abbrechen, nichts schreiben
        }

        // Neue Version ganz oben einfügen
        const allUpdates = [updateNode, ...existingUpdates];

        // Neues Objekt erzeugen
        const newXml = {
            ...xml,
            updates: {
                update: allUpdates
            }
        };

        let updatedXml = builder.build(newXml);

        // Hashes berechnen
        const archiveName = getArchiveName();
        log(`[xml] computing hashes for: ${archiveName}`);
        const sha256 = await createHashFromFile(archiveName, 'sha256');
        const sha384 = await createHashFromFile(archiveName, 'sha384');
        const sha512 = await createHashFromFile(archiveName, 'sha512');

        const shareplace = extend({}, stringsreplace, { "[SHA256]": sha256 }, { "[SHA384]": sha384 }, { "[SHA512]": sha512 });

        // Platzhalter ersetzen
        for (let [key, value] of Object.entries(shareplace)) {
            const re = new RegExp(key.replace('[', '\\[').replace(']', '\\]'), 'g');
            updatedXml = updatedXml.replace(re, value);
        }

        // Neue XML-Datei schreiben
        await fs.promises.writeFile(config.paths.updateXML.src, updatedXml, 'utf8');
        log(`[xml] updated: ${config.paths.updateXML.src}`);

    } catch (err) {
        console.error(`[xml] error: ${err?.message || err}`);
        process.exitCode = 1;
    }
};

updateXML();
