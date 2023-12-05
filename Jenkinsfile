def dockerImagecms

pipeline {

    agent any

    environment {
        SONAR_URL = 'http://10.129.2.232:9000'
        SONAR_SOURCES = './app/Models'
        SONAR_EXCLUSIONS = '**/*.html,**/*.phtml,**/*.css,**/*.js'
        PROJECT_KEY = '7_CMS_Service'
        PROJECT_NAME = 'CMS SERVICES'
        def BUILD_DATE = sh(script: "echo `date +%Y.%m.%d`", returnStdout: true).trim()
        APP_VERSION = "${env.BUILD_DATE}-${env.BRANCH_NAME}_${env.GIT_COMMIT}"
        REGISTRY_PGN = 'neuron/cms'
        SONAR_TOKEN = credentials('sonar-auth')
    }

    stages {
        
        stage('Code Quality') {
            agent {
                docker {
                    image 'sonarsource/sonar-scanner-cli:latest'
                    args '--entrypoint= --dns 10.129.1.3'
                }
            }
            steps {
                script{
                    try {
                        sh '''
                            sonar-scanner -Dsonar.qualitygate.wait=true -Dsonar.qualitygate.timeout=900 \
                            -Dsonar.host.url=$SONAR_URL \
                            -Dsonar.token=$SONAR_TOKEN \
                            -Dsonar.sourceEncoding=UTF-8 -Dsonar.projectName="$PROJECT_NAME $BRANCH_NAME" \
                            -Dsonar.projectKey=$PROJECT_KEY-$BRANCH_NAME \
                            -Dsonar.sources=$SONAR_SOURCES
                        '''
                    } catch (err) {
                        echo err.getMessage()
                    }
                }
            }
        }

        stage ('Pre Build App be') {
            agent {
                docker {
                    image 'composer:latest'
                    args '--entrypoint= --dns 10.129.1.3 -v /etc/passwd:/etc/passwd -v /etc/group:/etc/group'
                }
            }

            steps {
                script{
                    try {
                        sh 'composer install'
                    } catch (err) {
                        echo err.getMessage()
                    }
                }
                stash includes: "vendor/**", name: 'vendorArtifact'
            }
        }

        stage ('Pre Build App fe') {
            environment {
                HOME = '.'
            }
            agent {
                docker {
                    image 'node:20.10.0'
                    args '--entrypoint= --dns 10.129.1.3'
                }
            }

            steps {
                unstash 'vendorArtifact'
                sh '''
                    npm install
                    npm run build
                '''
                stash includes: "public/build/**", name: 'feArtifact'
            }
        }

        stage('Push Image') {
            steps {
                script {
                    
                    unstash 'vendorArtifact'
                    unstash 'feArtifact'

                    docker.withRegistry('https://hub.pgn.co.id', 'harbor-auth') {
                        dockerImagecms = docker.build "$REGISTRY_PGN" + ":${APP_VERSION}", "-f Dockerfile ."
                        dockerImagecms.push()
                    }
                }
            } 
        }

        stage('Deploy Apps') {
            steps {
                build job: 'GasKita/CMS_SERVICES/CMS_OPS',
                parameters: [
                    string(name: 'APP_VERSION', value: String.valueOf(APP_VERSION)),
                    string(name: 'PROJECT_NAME', value: String.valueOf(PROJECT_NAME)),
                    string(name: 'STAGE', value: String.valueOf(env.BRANCH_NAME))
                ]
            }
        }   
    }
}