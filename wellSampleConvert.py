#!/usr/bin/env python

import csv
import fileinput
import datetime

combinedFile = '/home/n0066922/wellsamples/sampleCombined.csv'
firstPassFile = '/home/n0066922/wellsamples/firstPass.csv'
finalFile = '/home/n0066922/wellsamples/finalFile.sql'

insertStub = "INSERT INTO WellSample (sampleID, wellID, chemID, sampleDate, pfcLevel, noteID) VALUES ('"

# Combine the CSV files
filenames = ['sample1.csv', 'sample2.csv', 'sample3.csv']
with open(combinedFile, 'w') as outfile:
    for fname in filenames:
        with open(fname) as infile:
            outfile.write(infile.read())


# Get rid of duplicate rows
rows = open(combinedFile).read().split('\n')
newrows = []

for row in rows:
    if row not in newrows and not row.startswith('USEPA'):
        if row.strip():
            newrows.append(row)

f = open(firstPassFile, 'w')
f.write('\n'.join(newrows))
f.close()


ifile = open(firstPassFile, 'rb')
reader = csv.reader(ifile)

linenum = 0

wellID = ""
chemID = ""
sampleDate = ""
pfcLevel = ""
noteID = ""

in_iter = ( (r[1], r[2], r[3], r[4], r[5], r[6], r[7], r[8], r[9],
             r[10], r[11], r[12], r[13], r[14], r[15], r[16],
             r[17], r[18], r[19], r[20], r[21], r[22], r[23],
             r[24], r[25], r[26]) for r in reader)


for line in in_iter:

    if linenum == 0:
        header = line
    else:
        wellID = 0

        if line[0].startswith('Smith'):
            wellID = 2
        if line[0].startswith('Harrison'):
            wellID = 3
        if line[0].startswith('Collins'):
            wellID = 4
        if line[0].startswith('Portsmouth'):
            wellID = 5
        if line[0].startswith('WWTP'):
            wellID = 6
        if line[0].startswith('DES'):
            wellID = 7
        if line[0].startswith('GBK_PR'):
            wellID = 8
        if line[0].startswith('GBK_DP'):
            wellID = 9
        if line[0].startswith('DSC_PRE'):
            wellID = 10
        if line[0].startswith('DSC-POST'):
            wellID = 11
        if line[0].startswith('Fire'):
            wellID = 12
        if line[0].startswith('CSW-1D'):
            wellID = 13
        if line[0].startswith('CSW-1S'):
            wellID = 14
        if line[0].startswith('CSW-2R'):
            wellID = 15
        if line[0].startswith('HMW-03'):
            wellID = 16
        if line[0].startswith('HMW-8R'):
            wellID = 17
        if line[0].startswith('HMW-14'):
            wellID = 18
        if line[0].startswith('HMW-15'):
            wellID = 19
        if line[0].startswith('SMW-A'):
            wellID = 20
        if line[0].startswith('SMW-1'):
            wellID = 21
        if line[0].startswith('SMW-13'):
            wellID = 22
        if line[0].startswith('PSW-1'):
            wellID = 23
        if line[0].startswith('PSW-2'):
            wellID = 24

        dateStr = datetime.datetime.strptime(line[2], "%m/%d/%Y").strftime("%Y-%m-%d")

        colnum = 0
        for col in line:
            if colnum > 2:
                noteID = 'null'

                if col[-2:] == ' J':
                    col = col[:-2]
                    noteID = 1
                if col[-2:] == ' B':
                    col = col[:-2]
                    noteID = 2
                if col[-2:] == ' D':
                    col = col[:-2]
                    noteID = 3
                if col == 'ND':
                    col = 0
                if col == 'NA':
                    col = 'null'
                # PFOA
                if colnum == 21:
                    print insertStub + line[1] + "', " + str(wellID) + ", 1, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFOS
                if colnum == 20:
                    print insertStub + line[1] + "', " + str(wellID) + ", 2, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFHxS
                if colnum == 16:
                    print insertStub + line[1] + "', " + str(wellID) + ", 3, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFHxA
                if colnum == 17:
                    print insertStub + line[1] + "', " + str(wellID) + ", 4, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFOSA
                if colnum == 19:
                    print insertStub + line[1] + "', " + str(wellID) + ", 5, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFBA
                if colnum == 10:
                    print insertStub + line[1] + "', " + str(wellID) + ", 6, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFBS
                if colnum == 9:
                    print insertStub + line[1] + "', " + str(wellID) + ", 7, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFDA
                if colnum == 12:
                    print insertStub + line[1] + "', " + str(wellID) + ", 8, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFDS
                if colnum == 11:
                    print insertStub + line[1] + "', " + str(wellID) + ", 9, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFDoA
                if colnum == 13:
                    print insertStub + line[1] + "', " + str(wellID) + ", 11, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFHpS
                if colnum == 12:
                    print insertStub + line[1] + "', " + str(wellID) + ", 12, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFHpA
                if colnum == 15:
                    print insertStub + line[1] + "', " + str(wellID) + ", 13, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFNA
                if colnum == 18:
                    print insertStub + line[1] + "', " + str(wellID) + ", 14, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFPeA
                if colnum == 22:
                    print insertStub + line[1] + "', " + str(wellID) + ", 15, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFTeDA
                if colnum == 23:
                    print insertStub + line[1] + "', " + str(wellID) + ", 16, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFTrDA
                if colnum == 24:
                    print insertStub + line[1] + "', " + str(wellID) + ", 17, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # PFUnA
                if colnum == 25:
                    print insertStub + line[1] + "', " + str(wellID) + ", 18, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # 6:2 FTS
                if colnum == 3:
                    print insertStub + line[1] + "', " + str(wellID) + ", 19, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # 8:2 FTS
                if colnum == 4:
                    print insertStub + line[1] + "', " + str(wellID) + ", 20, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # EtFOSA
                if colnum == 5:
                    print insertStub + line[1] + "', " + str(wellID) + ", 21, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # EtFOSE
                if colnum == 6:
                    print insertStub + line[1] + "', " + str(wellID) + ", 22, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # MEFOSA
                if colnum == 7:
                    print insertStub + line[1] + "', " + str(wellID) + ", 23, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"

                # MEFOSE
                if colnum == 8:
                    print insertStub + line[1] + "', " + str(wellID) + ", 24, "  + "'" + dateStr + "', " + str(col) + ", " + str(noteID) + ");"


            colnum += 1
    linenum += 1
ifile.close()
